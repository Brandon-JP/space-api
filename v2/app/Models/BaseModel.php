<?php

declare(strict_types=1);

namespace Vanier\Api\Models;

use PDO;
use Exception;
use Vanier\Api\Exceptions\HttpBadRequestException;
use Psr\Http\Message\ServerRequestInterface as Request;

use Vanier\Api\Helpers\InputsHelper;
use Vanier\Api\Helpers\PaginationHelper;

/**
 * A wrapper class for the PDO MySQL API.
 * This class can be extended for further customization.
 */
abstract class BaseModel
{

    /**
     * holds a handle to a database connection.
     */
    private $db;

    /**
     * The index of the current page.
     * @var int
     */
    private $current_page = 1;

    /**
     * Holds the number of records per page.
     * @var int
     */
    private $records_per_page = 5;

    /**
     * Instantiates the BaseModel.
     * @global array $db_config    database connection options.
     * @param array $options        Optional array of PDO options
     * @throws Exception 
     */
    public function __construct($options = [])
    {
        // Global array defined in includes/app_constants.php
        global $db_config;
        if (!isset($db_config['database'])) {
            throw new Exception('&args[\'database\'] is required');
        }

        if (!isset($db_config['username'])) {
            throw new Exception('&args[\'username\']  is required');
        }
        $default_options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
        $options = array_replace($default_options, $options);

        $host = $db_config['host'] ?? 'localhost';
        $charset = $db_config['charset'] ?? 'utf8mb4';
        $port = isset($db_config['port']) ? 'port=' . $db_config['port'] . ';' : '';
        $password = $db_config['password'] ?? '';
        $database = $db_config['database'];
        $username = $db_config['username'];

        $dsn = "mysql:host=$host;dbname=$database;port=$port;charset=utf8mb4";
        try {
            $this->db = new PDO($dsn, $username, $password, $options);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->query("SET NAMES utf8mb4");
            // Set the connection's character set.
            $this->db->query("SET NAMES $charset");
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Executes an SQL query using a prepared statement.
     * Arguments can be also passed to further filter the obtained result set.
     * @param  string $sql       sql query
     * @param  array  $args      filtering options that can be added to the query.
     * @return object            returns a PDO object
     */
    private function run($sql, $args = [])
    {
        if (empty($args)) {
            return $this->db->query($sql);
        }
        $stmt = $this->db->prepare($sql);
        //check if args is associative or sequential?
        $is_assoc = (array() === $args) ? false : array_keys($args) !== range(0, count($args) - 1);
        if ($is_assoc) {
            foreach ($args as $key => $value) {
                if (is_int($value)) {
                    $stmt->bindValue(":$key", $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue(":$key", $value);
                }
            }
            $stmt->execute();
        } else {
            $stmt->execute($args);
        }
        return $stmt;
    }

    /**
     * Executes the provided query.
     * 
     * @param  string $sql       sql query
     * @param  array  $args      filtering options that can be added to the query.
     * @param  object $fetchMode set return mode ie object or array
     * @return object            returns an array containing the selected records.
     */
    protected function fetchAll($sql, $args = [], $fetchMode = PDO::FETCH_ASSOC)
    {
        return $this->run($sql, $args)->fetchAll($fetchMode);
    }

    /**
     * Finds a record matching the provided filtering options.
     * Can execute a query that joins two or more tables. 
     * Should be used to fetch a single record from a table.
     * 
     * @param  string $sql       sql query
     * @param  array  $args      filtering options that will be appended to the WHERE clause.
     * @param  object $fetchMode set return mode ie object or array
     * @return object            returns single record
     */
    protected function fetchSingle($sql, $args = [], $fetchMode = PDO::FETCH_ASSOC)
    {
        return $this->run($sql, $args)->fetch($fetchMode);
    }


    /**
     * Gets the number of records contained in the obtained result set.
     * 
     * @param  string $sql       sql query
     * @param  array  $args      filtering options. 
     * @param  object $fetchMode set return mode ie object or array
     * @return integer           returns number of records
     */
    protected function count($sql, $args = []) : int
    {
        return $this->run($sql, $args)->rowCount();
    }

    /**
     * Gets primary key of last inserted record.
     * Note: should be used after a SELECT statement.
     */
    protected function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    /**
     * Inserts a new record into the specified table.
     * 
     * @param  string $table the table name where the new data should be inserted.
     * @param  array $data  an associative array of column names (fields) and values.
     *              For example, ["username"=>"frostybee", "email" =>"frostybee@me.com"]
     */
    protected function insert($table, $data)
    {
        //add columns into comma separated string
        $columns = implode(',', array_keys($data));

        //get values
        $values = array_values($data);

        $placeholders = array_map(function ($val) {
            return '?';
        }, array_keys($data));

        //convert array into comma separated string
        $placeholders = implode(',', array_values($placeholders));

        $this->run("INSERT INTO $table ($columns) VALUES ($placeholders)", $values);

        return $this->lastInsertId();
    }

    /**
     * updates one or more records contained in the specified table.
     * 
     * @param  string $table table name
     * @param  array $data  an array containing the names of the field(s) to be updated along with the new value(s).
     *                      For example, ["username"=>"frostybee", "email" =>"frostybee@me.com"]
     * @param  array $where an array containing the filtering operations (it should consist of column names and values)
     *                      For example, ["user_id"=> 3]
     */
    protected function update($table, $data, $where)
    {
        //merge data and where together
        $collection = array_merge($data, $where);

        //collect the values from collection
        $values = array_values($collection);

        //setup fields
        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = ?,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        //setup where 
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            $whereDetails .= $i == 0 ? "$key = ?" : " AND $key = ?";
            $i++;
        }

        $stmt = $this->run("UPDATE $table SET $fieldDetails WHERE $whereDetails", $values);

        return $stmt->rowCount();
    }

    /**
     * Deletes one or more records.
     * 
     * @param  string $table table name
     * @param  array $where an array containing the filtering operation. 
     * Note that those operations will eb appeNded to the WHERE Clause of the DELETE query. 
     * @param  integer $limit limit number of records
     */
    protected function delete($table, $where, $limit = 1)
    {
        //collect the values from collection
        $values = array_values($where);

        //setup where 
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            $whereDetails .= $i == 0 ? "$key = ?" : " AND $key = ?";
            $i++;
        }

        //if limit is a number use a limit on the query
        if (is_numeric($limit)) {
            $limit = "LIMIT $limit";
        }

        $stmt = $this->run("DELETE FROM $table WHERE $whereDetails $limit", $values);

        return $stmt->rowCount();
    }

    public function paginate(string $sql_query, array $args = [], $fetchMode = PDO::FETCH_ASSOC)
    {
        $total_row_count = $this->count($sql_query, $args);
        $pagination_helper = new PaginationHelper($this->current_page, $this->records_per_page, $total_row_count);

        $page_offset = $pagination_helper->getOffset();
        $sql_query .= " LIMIT $page_offset, $this->records_per_page ";
        $pagination_info = $pagination_helper->getPaginationInfo();
        $pagination_info["data"] = $this->fetchAll($sql_query, $args);
        return $pagination_info;
    }

    public function setPaginationOptions(Request $request, mixed $current_page, mixed $records_per_page): void
    {
        $current_page_valid = InputsHelper::isInt($current_page, 1);
        $records_per_page_valid = InputsHelper::isInt($records_per_page, 1);
        
        if($current_page_valid && $records_per_page_valid)
        {
            $this->current_page = $current_page_valid;
            $this->records_per_page = $records_per_page_valid;
        }
        else if(!$current_page_valid){
            $invalid_records_per_page_filters_message = "The current page number can only be a number equal to 1 or greater.";
            throw new HttpBadRequestException($request, $invalid_records_per_page_filters_message);
        }
        else if(!$records_per_page_valid){
            $invalid_records_per_page_filters_message = "The page size can only be a number equal to 1 or greater.";
            throw new HttpBadRequestException($request, $invalid_records_per_page_filters_message);
        }
    }

    /**
     * Adds a sorting clause to a sql query string
     * @param string $sql_query The SQL query to which a sorting clause should be added
     * @param string $sorting_column The sorting column that should be used
     * @param string $sorting_order The sorting order of the sorting clause
     * @return string The resulting SQL query
     */
    public function addSortingClause(string $sql_query, string $sorting_column, string $sorting_order = "ascending") : string
    {
        if(mb_strlen($sorting_column, "UTF-8") > 0)
        {
            $sql_query .= " ORDER BY $sorting_column ";
            $sql_query = $this->addSortingOrder($sql_query, $sorting_order);
        }
        return $sql_query;
    }

    /**
     * Adds a sorting order (ASC or DESC) to a SQL query
     * @param string $sql_query The SQL query string that should receive the sorting order
     * @param string $sorting_order The sorting order used to decide which sorting order should be used. This can be ascending or descending
     * @return string The resulting SQL query
     */
    private function addSortingOrder(string $sql_query, string $sorting_order) : string
    {
        switch($sorting_order)
        {
            case "ascending":
                $sql_query .= " ASC ";
                break;
            case "descending":
                $sql_query .= " DESC ";
                break;
            default:
                $sql_query .= " ASC ";
        }
        return $sql_query;
    }
}
