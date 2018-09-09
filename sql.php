<?php

class Sql {

    /**
     * I use this 5 private vars in my functions to connect with db and make some changes
     */
    private $_connection = null;
    private $_inner_join = null;
    private $_where = null;
    private $_order_by = null;
    private $_limit_offset = null;


    /**
     * Sql constructor.
     * Constructor helps to connect with database.
     *
     * @param $host - host name of database (localhost)
     * @param $user - user name (root)
     * @param $password - password of db (1234)
     * @param $db_name - name of db (user.kg)
     */
    public function __construct($host, $user, $password, $db_name)
    {
        $this->_connection = mysqli_connect($host, $user, $password, $db_name);
        mysqli_set_charset($this->_connection, "utf8");
        return $this->_connection;
    }

    /**
     *  Method innerJoin() helps to join with another table.
     *
     * @param $table_name - join table name with the main table
     * @param $inner_join_value - helps to join the same columns
     */
    public function innerJoin($table_name, $inner_join_value) {
        $this->_inner_join = "INNER JOIN $table_name ON $inner_join_value";
    }

    /**
     * Method where() helps to find notes in db by determined column
     *
     * @param string $column_name - name of column in db
     * @param string $column_value - value, filter by this value
     * @param string $symbol - operator. = by default.
     */
    public function where($column_name, $column_value, $symbol="=") {
        $column_value = mysqli_real_escape_string($this->_connection, $column_value);

        if ($symbol == ">") {
            $this->_where = "WHERE $column_name > '$column_value'";
        } else if ($symbol == "<") {
            $this->_where = "WHERE $column_name < '$column_value'";
        } else if ($symbol == "!=") {
            $this->_where = "WHERE $column_name != '$column_value'";
        } else if ($symbol == "LIKE") {
            $this->_where = "WHERE $column_name LIKE '$column_value'";
        } else {
            $this->_where = "WHERE $column_name = '$column_value'";
        }
    }

    /**
     * Method orderBy() helps to sort some notes.
     * @param null $order_by_value - parameter to sort by some column
     */
    public function orderBy($order_by_value = null) {
        $this->_order_by = "ORDER BY $order_by_value";
    }

    /**
     * Method limitOffset() shows determined count of notes
     *
     * @param $limit_value - helps how much notes to show
     * @param $offset_value - shows some notes after some number
     */
    public function limitOffset($limit_value, $offset_value) {
        $this->_limit_offset = "LIMIT $limit_value OFFSET $offset_value";
    }

    /**
     * Method andInnerJoin() helps to connect with 2 or more table. It is using only after method InnerJoin.
     * @param $table_name
     * @param $inner_join_value
     */
    public function andInnerJoin($table_name, $inner_join_value) {
        $this->_inner_join .= " INNER JOIN $table_name ON $inner_join_value";
    }

    /**
     * Method orWhere() helps to find notes with 2 or more columns. It is using only after method where.
     *
     * @param $column_name
     * @param $column_value
     * @param string $symbol
     */

    public function orWhere($column_name, $column_value, $symbol="=") {
        $column_value = mysqli_real_escape_string($this->_connection, $column_value);

        if ($symbol == ">") {
            $this->_where .= " OR $column_name > '$column_value'";
        } else if ($symbol == "<") {
            $this->_where .= " OR $column_name < '$column_value'";
        } else if ($symbol == "!=") {
            $this->_where .= " OR $column_name != '$column_value'";
        } else if ($symbol == "LIKE") {
            $this->_where .= " OR $column_name LIKE '$column_value'";
        } else {
            $this->_where .= " OR $column_name = '$column_value'";
        }
    }

    /**
     * Method andWhere() helps to find notes with 2 and more columns. It is using only after method where.
     *
     * @param $column_name
     * @param $column_value
     * @param string $symbol
     */
    public function andWhere($column_name, $column_value, $symbol="=") {
        $column_value = mysqli_real_escape_string($this->_connection, $column_value);

        if ($symbol == ">") {
            $this->_where .= " AND $column_name > '$column_value'";
        } else if ($symbol == "<") {
            $this->_where .= " AND $column_name < '$column_value'";
        } else if ($symbol == "!=") {
            $this->_where .= " AND $column_name != '$column_value'";
        } else if ($symbol == "LIKE") {
            $this->_where .= " AND $column_name LIKE '$column_value'";
        } else {
            $this->_where .= " AND $column_name = '$column_value'";
        }
    }

    /**
     * Method get() helps to get some notes in db.
     * @param $table_name - name of table in db
     * @param string $select_rows - helps to get determined rows. By default, it selects * rows
     * @return array - result of getting notes.
     */
    public function get($table_name, $select_rows = '*') {
        $result = mysqli_query($this->_connection,"SELECT $select_rows FROM $table_name $this->_inner_join $this->_where $this->_order_by $this->_limit_offset");
        $database_array = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $database_array[] = $row;
        }
        return $database_array;
    }

    /**
     * Method insert_id() helps to get identifier of last insert id.
     */
    public function insert_id()  {
        return mysqli_insert_id($this->_connection);
    }

    /**
     * Method num_rows() helps to find count of notes with determined parameters.
     *
     * @param $table_name
     * @param string $select_rows
     * @return int
     */
    public function num_rows($table_name, $select_rows = '*') {
        $result = mysqli_query($this->_connection,"SELECT $select_rows FROM $table_name $this->_inner_join $this->_where $this->_order_by");
        return mysqli_num_rows($result);
    }

    /**
     * Method Insert() helps to insert note into database.
     *
     * @param $table_name
     * @param $insert_array - array of data, which save in db.
     */
    public function insert($table_name, $insert_array) {
        $columns = implode(", ", array_keys($insert_array));
        $link = $this->_connection;
        $values = array_map(function($val) use($link) {
            return mysqli_real_escape_string($link, $val);
        }, $insert_array);
        $values = "'" . implode("', '", $values) . "'";

        mysqli_query($this->_connection, "INSERT INTO $table_name ($columns) VALUES ($values)");
    }

    /**
     * Method delete() deletes one note from some table.
     *
     * @param $table_name
     */
    public function delete($table_name) {
        mysqli_query($this->_connection, "DELETE FROM $table_name $this->_where");
    }

    /**
     * Method update() helps to update some note in table.
     *
     * @param $table_name
     * @param $update_array - array of some data which save in table.
     */
    function update($table_name, $update_array) {
        $str = [];

        foreach ($update_array as $column => $value) {
            $_column = mysqli_real_escape_string($this->_connection, $column);
            $_value = mysqli_real_escape_string($this->_connection, $value);

            $str[] = $_column . '= "' . $_value . '"';
        }

        mysqli_query($this->_connection, "UPDATE $table_name SET " . implode(',', $str) ." $this->_where");
    }
}

class Users extends Sql {

    /**
     * I get all users by method getUsers(). By default, I get 5 users in 1 page.
     *
     * @param $page - parameter of current page
     * @param $order_by - parameter of sort
     * @param $limit - parameter of limit
     * @return array
     */
    public function getUsers($page, $order_by, $limit) {
        $offset = ($page - 1) * $limit;
        $this->orderBy($order_by);
        $this->limitOffset($limit, $offset);
        return $this->get('users');
    }

    /**
     * I get info about one user by method getOneUserById()
     *
     * @param $id - number of some note of user
     * @return array
     */
    public function getOneUserById($id) {
        $this->where('id', $id);
        return $this->get('users');
    }

    /**
     * I get just identifiers of all users (because it works faster than getting all rows) and divide by limit of users to get count of pages
     *
     * @param $limit
     * @return float
     */
    public function getCountPages($limit) {
        $num_rows = $this->num_rows('users', 'id');
        $count_pages = ceil($num_rows / $limit);
        return $count_pages;
    }

    /**
     *  I can get some column by login and password, for example: nickname, surname, birthdate etc by method getColumnByLoginAndPassword()
     *
     * @param $column - parameter, where I find some column
     * @param $login
     * @param $password
     * @return mixed
     */
    public function getColumnByLoginAndPassword($column, $login, $password) {
        $this->where("login", $login);
        $this->andWhere("password", $password);
        $users = $this->get('users');
        foreach ($users as $user) {
            $column = $user[$column];
        }
        return $column;
    }

    /**
     * I get some column by id by method getColumnById().
     * @param $column
     * @param $id
     * @return mixed
     */
    public function getColumnById($column, $id) {
        $this->where('id', $id);
        $users = $this->get('users');
        foreach ($users as $user) {
            $column = $user[$column];
        }
        return $column;
    }

    /**
     * I check count of users with login named in parameter $login. If count == 1, I don't insert new note, because login must be unique.
     *
     * @param $login - must be unique
     * @return int
     */
    public function getNumRowsByLogin($login) {
        $this->where('login', $login);
        $num_rows = $this->num_rows('users');
        return $num_rows;
    }

    /**
     * When user updates his data and doesn't want to change his login, this function gives a chance to leave current login.
     *
     * @param $login
     * @param $current_login
     * @return int
     */
    public function getNumRowsByLoginAndCurrentLogin($login, $current_login) {
        $this->where('login', $login);
        $this->andWhere('login', $current_login, '!=');
        $num_rows = $this->num_rows('users');
        return $num_rows;
    }

    /**
     * Checking, when admin or user are logged in account
     * @param $login
     * @param $password
     * @return int
     */
    public function authorization($login, $password) {
        $this->where("login", $login);
        $this->andWhere("password", $password);
        return $this->num_rows("users");
    }

    // Next 3 methods are accessed only for admins, not for users.

    public function insertUser($data) {
        $this->insert('users', $data);
    }
    public function deleteUserById($id) {
        $this->where('id', $id);
        $this->delete('users');
    }
    public function updateUserById($id, $data) {
        $this->where('id', $id);
        $this->update('users', $data);
    }
}