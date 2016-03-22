<?php
namespace VersionPress\Database;

/**
 * DB access class used by VersionPress, and VersionPress only. It is a proxy to `$wpdb` with the same API
 * but always uses the original, unrewritten `query()` implementation. The client code usually just accesses
 * the `$database` object which it gets from the DI container. VersionPress code almost never uses $wpdb directly.
 * 
 * To explain the motivation a little bit, when VersionPress is installed, it rewrites the `wpdb` class
 * and adds its own logic into DB-manipulating methods. That is good for WordPress and all the 3rd party
 * plugins but not for VersionPress itself - it needs the raw `query()` functionality not to trigger
 * itself recursively. Now, the developers could call `$wpdb->__wp_query()` but that's ugly,
 * fragile and easy to forget. This proxy improves that.
 *
 * (The PhpDoc API description below can be regenerated by `./wpdb-api-to-phpdoc.php`.)
 *
 * @property bool show_errors
 * @property bool suppress_errors
 * @property string last_error
 * @property int num_queries
 * @property int num_rows
 * @property int rows_affected
 * @property int insert_id
 * @property array last_query
 * @property array|null last_result
 * @property array queries
 * @property string prefix
 * @property string base_prefix
 * @property bool ready
 * @property int blogid
 * @property int siteid
 * @property array tables
 * @property array old_tables
 * @property array global_tables
 * @property array ms_global_tables
 * @property string comments
 * @property string commentmeta
 * @property string links
 * @property string options
 * @property string postmeta
 * @property string posts
 * @property string terms
 * @property string term_relationships
 * @property string term_taxonomy
 * @property string termmeta
 * @property string usermeta
 * @property string users
 * @property string blogs
 * @property string blog_versions
 * @property string registration_log
 * @property string signups
 * @property string site
 * @property string sitecategories
 * @property string sitemeta
 * @property array field_types
 * @property string charset
 * @property string collate
 * @property string func_call
 * @property bool is_mysql
 * @method init_charset()
 * @method set_charset($dbh, $charset, $collate)
 * @method set_sql_mode($modes)
 * @method string|\WP_Error set_prefix($prefix, $set_table_names)
 * @method int set_blog_id($blog_id, $site_id)
 * @method string get_blog_prefix($blog_id)
 * @method array tables($scope, $prefix, $blog_id)
 * @method select($db, $dbh)
 * @method string _weak_escape($string)
 * @method string _real_escape($string)
 * @method string|array _escape($data)
 * @method mixed escape($data)
 * @method escape_by_ref($string)
 * @method string|void prepare($query, $args)
 * @method string esc_like($text)
 * @method false|void print_error($str)
 * @method bool show_errors($show)
 * @method bool hide_errors()
 * @method bool suppress_errors($suppress)
 * @method flush()
 * @method bool db_connect($allow_bail)
 * @method bool|void check_connection($allow_bail)
 * @method int|false replace($table, $data, $format)
 * @method int|false _insert_replace_helper($table, $data, $format, $type)
 * @method string|null get_var($query, $x = 0, $y = 0)
 * @method array|object|null|void get_row($query = null, $output = OBJECT, $y = 0)
 * @method array get_col($query, $x = 0)
 * @method array|object|null get_results($query, $output)
 * @method string|false|\WP_Error get_col_charset($table, $column)
 * @method array|false|\WP_Error get_col_length($table, $column)
 * @method string|\WP_Error strip_invalid_text_for_column($table, $column, $value)
 * @method mixed get_col_info($info_type, $col_offset)
 * @method true timer_start()
 * @method float timer_stop()
 * @method false|void bail($message, $error_code)
 * @method \WP_Error|void check_database_version()
 * @method bool supports_collation()
 * @method string get_charset_collate()
 * @method int|false has_cap($db_cap)
 * @method string|array get_caller()
 * @method null|string db_version()
 * @method int|false insert($table, $data, $format)
 * @method int|false update($table, $data, $where, $format, $where_format)
 * @method int|false delete($table, $where, $where_format)
 */
class Database {

    /**
     * @var \wpdb
     */
    private $wpdb;


    public function __construct($wpdb) {
        $this->wpdb = $wpdb;
    }

    public function __get($name) {
        return $this->wpdb->$name;
    }

    function __set($name, $value) {
        $this->wpdb->$name = $value;
    }

    function __call($name, $arguments) {
        return call_user_func_array([$this->wpdb, $name], $arguments);
    }


    /**
     * @see \wpdb::query()
     * @param string $query Database query
     * @return int|false Number of rows affected/selected or false on error
     */
    public function query($query) {
        $rawQueryMethodName = "__wp_query";
        if (method_exists("wpdb", $rawQueryMethodName)) {
            return $this->wpdb->$rawQueryMethodName($query);
        } else {
            return $this->wpdb->query($query);
        }
    }

}
