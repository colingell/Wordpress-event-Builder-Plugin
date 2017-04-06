<?php
/*
Plugin Name: Wheres The Party App
Plugin URI: http://trepidation.co.uk/colingell
Description: Where's the party app
Author: Colin Gell
Version: 1.0
Author URI: http://trepidation.co.uk
*/

/**
 * PART 1. Defining Custom Database Table
 * ============================================================================
 *
 * In this part you are going to define custom database table,
 * create it, update, and fill with some dummy data
 *
 * http://codex.wordpress.org/Creating_Tables_with_Plugins
 *
 * In case your are developing and want to check plugin use:
 *
 * DROP TABLE IF EXISTS wp_wherespartyapp;
 * DELETE FROM wp_options WHERE option_name = 'custom_table_wheres_party_app_install_data';
 *
 * to drop table and option
 */

/**
 * $custom_table_wheres_party_app - holds current database version
 * and used on plugin update to sync database tables
 */
global $custom_table_wheres_party_app;
$custom_table_wheres_party_app = '1.2'; // version changed from 1.0 to 1.1

/**
 * register_activation_hook implementation
 *
 * will be called when user activates plugin first time
 * must create needed database tables
 */
function custom_table_wheres_party_app_install()
{
    global $wpdb;
    global $custom_table_wheres_party_app;

    $table_wtpa = $wpdb->prefix . 'wherespartyapp'; // do not forget about tables prefix

    // sql to create your table
    // NOTICE that:
    // 1. each field MUST be in separate line
    // 2. There must be two spaces between PRIMARY KEY and its name
    //    Like this: PRIMARY KEY[space][space](id)
    // otherwise dbDelta will not work
    $sql = "CREATE TABLE " . $table_wtpa . " (
      id int(11) NOT NULL AUTO_INCREMENT,
      eventname tinytext NOT NULL,
      contactemail VARCHAR(100) NOT NULL,
      agerange int(11) NULL,
      dateofevent VARCHAR(100) NOT NULL,
      starttime VARCHAR(100) NOT NULL,
      finishtime VARCHAR(100) NOT NULL,
      contactnumber VARCHAR(100) NOT NULL,
      genre VARCHAR(100) NOT NULL,
      doorprice VARCHAR(100) NOT NULL,
      guestlistprice VARCHAR(100) NOT NULL,
      bookedtableprice VARCHAR(100) NOT NULL,
      latenight VARCHAR(100) NOT NULL,
      dresscode VARCHAR(100) NOT NULL,
      description VARCHAR(100) NOT NULL,
      address VARCHAR(100) NOT NULL,
      postcode VARCHAR(100) NOT NULL,
      image VARCHAR(100) NOT NULL,    
      PRIMARY KEY  (id)
    );";

    // we do not execute sql directly
    // we are calling dbDelta which cant migrate database
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // save current database version for later use (on upgrade)
    add_option('custom_table_wheres_party_app', $custom_table_wheres_party_app);

    /**
     * [OPTIONAL] Example of updating to 1.1 version
     *
     * If you develop new version of plugin
     * just increment $custom_table_wheres_party_app variable
     * and add following block of code
     *
     * must be repeated for each new version
     * in version 1.1 we change email field
     * to contain 200 chars rather 100 in version 1.0
     * and again we are not executing sql
     * we are using dbDelta to migrate table changes
     */
    $installed_ver = get_option('custom_table_wheres_party_app');
    if ($installed_ver != $custom_table_wheres_party_app) {
        $sql = "CREATE TABLE " . $table_wtpa . " (
          id int(11) NOT NULL AUTO_INCREMENT,
          eventname tinytext NOT NULL,
          contactemail VARCHAR(200) NOT NULL,
          agerange int(11) NULL,
          dateofevent VARCHAR(100) NOT NULL,
      starttime VARCHAR(100) NOT NULL,
      finishtime VARCHAR(100) NOT NULL,
      contactnumber VARCHAR(100) NOT NULL,
      genre VARCHAR(100) NOT NULL,
      doorprice VARCHAR(100) NOT NULL,
      guestlistprice VARCHAR(100) NOT NULL,
      bookedtableprice VARCHAR(100) NOT NULL,
      latenight VARCHAR(100) NOT NULL,
      dresscode VARCHAR(100) NOT NULL,
      description VARCHAR(100) NOT NULL,
      address VARCHAR(100) NOT NULL,
      postcode VARCHAR(100) NOT NULL,
      image VARCHAR(100) NOT NULL,      
          PRIMARY KEY  (id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // notice that we are updating option, rather than adding it
        update_option('custom_table_wheres_party_app', $custom_table_wheres_party_app);
    }
}
register_activation_hook(__FILE__, 'custom_table_wheres_party_app_install');

/**
 * register_activation_hook implementation
 *
 * [OPTIONAL]
 * additional implementation of register_activation_hook
 * to insert some dummy data
 */
function custom_table_wheres_party_app_install_data()
{
    global $wpdb;

    $table_wtpa = $wpdb->prefix . 'wherespartyapp'; // do not forget about tables prefix

    $wpdb->insert($table_wtpa, array(
        'eventname' => 'Late Party',
        'dateofevent' => '02/01/2016',
        'starttime' => '04:00',
        'finishtime' => '10:00',
        'contactemail' => 'adam@example.com',
        'contactnumber' => '0123456789',
        'genre' => 'soul house',
        'doorprice' => '15.00',
        'guestlistprice' => '10.00',
        'bookedtableprice' => '20.00',
        'latenight' => 'yes',
        'agerange' => '18+',
        'dresscode' => 'casual',
        'description' => 'Really cool party, that rocks',
        'address' => 'The Funky Box, Funk Street, FunkTown, Rockshire',
        'postcode' => 'SK11 1AA',
        'image' => '/images/01/16/image2.jpg'    
    ));
    $wpdb->insert($table_wtpa, array(
        'eventname' => 'Big Party',
        'dateofevent' => '01/01/2016',
        'starttime' => '24:00',
        'finishtime' => '02:00',
        'contactemail' => 'maria@example.com',
        'contactnumber' => '0123456789',
        'genre' => 'garage',
        'doorprice' => '10.00',
        'guestlistprice' => '20.00',
        'bookedtableprice' => '30.00',
        'latenight' => 'no',
        'agerange' => '21+',
        'dresscode' => 'smart casual',
        'description' => 'Really cool party, that rocks',
        'address' => 'The Funky Box, Funk Street, FunkTown, Rockshire',
        'postcode' => 'SK11 1AA',
        'image' => '/images/01/16/image.jpg'    
    ));
}

register_activation_hook(__FILE__, 'custom_table_wheres_party_app_install_data');

/**
 * Trick to update plugin database, see docs
 */
function custom_table_wtpa_update_db_check()
{
    global $custom_table_wheres_party_app;
    if (get_site_option('custom_table_wheres_party_app') != $custom_table_wheres_party_app) {
        custom_table_wheres_party_app_install();
    }
}

add_action('plugins_loaded', 'custom_table_wtpa_update_db_check');

/**
 * PART 2. Defining Custom Table List
 * ============================================================================
 *
 * In this part you are going to define custom table list class,
 * that will display your database records in nice looking table
 *
 * http://codex.wordpress.org/Class_Reference/WP_List_Table
 * http://wordpress.org/extend/plugins/custom-list-table-example/
 */

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * custom_table_wtpa_List_Table class that will display our custom table
 * records in nice table
 */
class custom_table_wtpa_List_Table extends WP_List_Table
{
    /**
     * [REQUIRED] You must declare constructor and give some basic params
     */
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'person',
            'plural' => 'persons',
        ));
    }

    /**
     * [REQUIRED] this is a default column renderer
     *
     * @param $item - row (key, value array)
     * @param $column_name - string (key)
     * @return HTML
     */
    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    /**
     * [OPTIONAL] this is example, how to render specific column
     *
     * method name must be like this: "column_[column_name]"
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_age($item)
    {
        return '<em>' . $item['age'] . '</em>';
    }

    /**
     * [OPTIONAL] this is example, how to render column with actions,
     * when you hover row "Edit | Delete" links showed
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_name($item)
    {
        // links going to /admin.php?page=[your_plugin_page][&other_params]
        // notice how we used $_REQUEST['page'], so action will be done on curren page
        // also notice how we use $this->_args['singular'] so in this example it will
        // be something like &person=2
        $actions = array(
            'edit' => sprintf('<a href="?page=persons_form&id=%s">%s</a>', $item['id'], __('Edit', 'custom_table_wtpa')),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete', 'custom_table_wtpa')),
        );

        return sprintf('%s %s',
            $item['name'],
            $this->row_actions($actions)
        );
    }

    /**
     * [REQUIRED] this is how checkbox column renders
     *
     * @param $item - row (key, value array)
     * @return HTML
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    /**
     * [REQUIRED] This method return columns to display in table
     * you can skip columns that you do not want to show
     * like content, or description
     *
     * @return array
     */
    function get_columns()
    {
        $columns = array(           
          'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
        'eventname' => __('Event Name', 'custom_table_wtpa'),
        'dateofevent' => __('Date of Event', 'custom_table_wtpa'),
        'starttime' => __('Start Time', 'custom_table_wtpa'),
        'finishtime' => __('Finish Time', 'custom_table_wtpa'),
        'contactemail' => __('E-Mail', 'custom_table_wtpa'),
        'contactnumber' => __('Telephone', 'custom_table_wtpa'),
        'genre' => __('Genre', 'custom_table_wtpa'),
        'doorprice' => __('Door Price', 'custom_table_wtpa'),
        'guestlistprice' => __('Guest List Price', 'custom_table_wtpa'),
        'bookedtableprice' => __('Booked Table Price', 'custom_table_wtpa'),
        'latenight' => __('Late Night Event', 'custom_table_wtpa'),
        'agerange' => __('Age Range', 'custom_table_wtpa'),
        'dresscode' => __('Dress Code', 'custom_table_wtpa'),
        'description' => __('Event Description', 'custom_table_wtpa'),
        'address' => __('Event Address', 'custom_table_wtpa'),
        'postcode' => __('Postcode', 'custom_table_wtpa'),
        'image' => __('Flyer ', 'custom_table_wtpa') 
        );
        return $columns;
    }

    /**
     * [OPTIONAL] This method return columns that may be used to sort table
     * all strings in array - is column names
     * notice that true on name column means that its default sort
     *
     * @return array
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
        'eventname' => array('eventname', true),
        'dateofevent' => array('dateofevent', true),
        'starttime' => array('starttime', true),
        'finishtime' => array('finishtime', true),
        'contactemail' => array('contactemail', false),
        'contactnumber' => array('contactnumber', false),
        'genre' => array('genre', true),
        'doorprice' => array('doorprice', true),
        'guestlistprice' => array('guestlistprice', true),
        'bookedtableprice' => array('bookedtableprice', true),
        'latenight' => array('latenight', true),
        'agerange' => array('agerange', true),
        'dresscode' => array('dresscode', true),
        'description' => array('description', false),
        'address' => array('address', true),
        'postcode' => array('postcode', true),
        'image' => array('image', false)
        );
        return $sortable_columns;
    }

    /**
     * [OPTIONAL] Return array of bult actions if has any
     *
     * @return array
     */
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    /**
     * [OPTIONAL] This method processes bulk actions
     * it can be outside of class
     * it can not use wp_redirect coz there is output already
     * in this example we are processing delete action
     * message about successful deletion will be shown on page in next part
     */
    function process_bulk_action()
    {
        global $wpdb;
        $table_wtpa = $wpdb->prefix . 'wherespartyapp'; // do not forget about tables prefix

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_wtpa WHERE id IN($ids)");
            }
        }
    }

    /**
     * [REQUIRED] This is the most important method
     *
     * It will get rows from database and prepare them to be showed in table
     */
    function prepare_items()
    {
        global $wpdb;
        $table_wtpa = $wpdb->prefix . 'wherespartyapp'; // do not forget about tables prefix

        $per_page = 5; // constant, how much records will be shown per page

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_wtpa");

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'eventname';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_wtpa ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);

        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}

/**
 * PART 3. Admin page
 * ============================================================================
 *
 * In this part you are going to add admin page for custom table
 *
 * http://codex.wordpress.org/Administration_Menus
 */

/**
 * admin_menu hook implementation, will add pages to list persons and to add new one
 */
function custom_table_wtpa_admin_menu()
{
    add_menu_page(__('Persons', 'custom_table_wtpa'), __('Wheres The Party App', 'custom_table_wtpa'), 'activate_plugins', 'persons', 'custom_table_wtpa_persons_page_handler',
'dashicons-star-filled', 14 );
    add_submenu_page('persons', __('Events', 'custom_table_wtpa'), __('Events', 'custom_table_wtpa'), 'activate_plugins', 'persons', 'custom_table_wtpa_persons_page_handler');
    // add new will be described in next part
    add_submenu_page('persons', __('Add new', 'custom_table_wtpa'), __('Add new', 'custom_table_wtpa'), 'activate_plugins', 'persons_form', 'custom_table_wtpa_persons_form_page_handler');
}

add_action('admin_menu', 'custom_table_wtpa_admin_menu');

/**
 * List page handler
 *
 * This function renders our custom table
 * Notice how we display message about successfull deletion
 * Actualy this is very easy, and you can add as many features
 * as you want.
 *
 * Look into /wp-admin/includes/class-wp-*-list-table.php for examples
 */
function custom_table_wtpa_persons_page_handler()
{
    global $wpdb;

    $table = new custom_table_wtpa_List_Table();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'custom_table_wtpa'), count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
<div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Events', 'custom_table_wtpa')?> <a class="add-new-h2"
                                 href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=persons_form');?>"><?php _e('Add new', 'custom_table_wtpa')?></a>
    </h2>
    <?php echo $message; ?>

    <form id="persons-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>

</div>
<?php
}

/**
 * PART 4. Form for adding and or editing row
 * ============================================================================
 *
 * In this part you are going to add admin page for adding and or editing items
 * You cant put all form into this function, but in this example form will
 * be placed into meta box, and if you want you can split your form into
 * as many meta boxes as you want
 *
 * http://codex.wordpress.org/Data_Validation
 * http://codex.wordpress.org/Function_Reference/selected
 */

/**
 * Form page handler checks is there some data posted and tries to save it
 * Also it renders basic wrapper in which we are callin meta box render
 */
function custom_table_wtpa_persons_form_page_handler()
{
    global $wpdb;
    $table_wtpa = $wpdb->prefix . 'wherespartyapp'; // do not forget about tables prefix

    $message = '';
    $notice = '';

    // this is default $item which will be used for new records
    $default = array(
        'id' => 0,
        'eventname' => '',
        'dateofevent' => '',
        'starttime' => '',
        'finishtime' => '',
        'contactemail' => '',
        'contactnumber' => '',
        'genre' => '',
        'doorprice' => '',
        'guestlistprice' => '',
        'bookedtableprice' => '',
        'latenight' => '',
        'agerange' => '',
        'dresscode' => '',
        'description' => '',
        'address' => '',
        'postcode' => '',
        'image' => '/images/01/16/default.jpg' 
    );

    // here we are verifying does this request is post back and have correct nonce
    if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        // combine our default item with request params
        $item = shortcode_atts($default, $_REQUEST);
        // validate data, and if all ok save item to database
        // if id is zero insert otherwise update
        $item_valid = custom_table_wtpa_validate_person($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $result = $wpdb->insert($table_wtpa, $item);
                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = __('Item was successfully saved', 'custom_table_wtpa');
                } else {
                    $notice = __('There was an error while saving item', 'custom_table_wtpa');
                }
            } else {
                $result = $wpdb->update($table_wtpa, $item, array('id' => $item['id']));
                if ($result) {
                    $message = __('Item was successfully updated', 'custom_table_wtpa');
                } else {
                    $notice = __('There was an error while updating item', 'custom_table_wtpa');
                }
            }
        } else {
            // if $item_valid not true it contains error message(s)
            $notice = $item_valid;
        }
    }
    else {
        // if this is not post back we load item to edit or give new one to create
        $item = $default;
        if (isset($_REQUEST['id'])) {
            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_wtpa WHERE id = %d", $_REQUEST['id']), ARRAY_A);
            if (!$item) {
                $item = $default;
                $notice = __('Item not found', 'custom_table_wtpa');
            }
        }
    }

    // here we adding our custom meta box
    add_meta_box('persons_form_meta_box', 'Events data', 'custom_table_wtpa_persons_form_meta_box_handler', 'person', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Event', 'custom_table_wtpa')?> <a class="add-new-h2"
                                href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=persons');?>"><?php _e('back to list', 'custom_table_wtpa')?></a>
    </h2>

    <?php if (!empty($notice)): ?>
    <div id="notice" class="error"><p><?php echo $notice ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
    <div id="message" class="updated"><p><?php echo $message ?></p></div>
    <?php endif;?>

    <form id="form" method="POST">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
        <?php /* NOTICE: here we storing id to determine will be item added or updated */ ?>
        <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>

        <div class="metabox-holder" id="poststuff">
            <div id="post-body">
                <div id="post-body-content">
                    <?php /* And here we call our custom meta box */ ?>
                    <?php do_meta_boxes('person', 'normal', $item); ?>
                    <input type="submit" value="<?php _e('Save', 'custom_table_wtpa')?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}

/**
 * This function renders our custom meta box
 * $item is row
 *
 * @param $item
 */
function custom_table_wtpa_persons_form_meta_box_handler($item)
{
    ?>

<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="eventname"><?php _e('Event Name', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="eventname" name="eventname" type="text" style="width: 95%" value="<?php echo esc_attr($item['eventname'])?>"
                   size="50" class="code" placeholder="<?php _e('Event name', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="dateofevent"><?php _e('Date of Event', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="dateofevent" name="dateofevent" type="text" style="width: 95%" value="<?php echo esc_attr($item['dateofevent'])?>"
                   size="50" class="code" placeholder="<?php _e('DD/MM/YYYY', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="starttime"><?php _e('Start Time', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="starttime" name="starttime" type="text" style="width: 95%" value="<?php echo esc_attr($item['starttime'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="finishtime"><?php _e('Finish Time', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="finishtime" name="finishtime" type="text" style="width: 95%" value="<?php echo esc_attr($item['finishtime'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="contactemail"><?php _e('Contact E-mail', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="contactemail" name="contactemail" type="text" style="width: 95%" value="<?php echo esc_attr($item['contactemail'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="contactnumber"><?php _e('Contact Number', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="contactnumber" name="contactnumber" type="text" style="width: 95%" value="<?php echo esc_attr($item['contactnumber'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="genre"><?php _e('Genre', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="genre" name="genre" type="text" style="width: 95%" value="<?php echo esc_attr($item['genre'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="doorprice"><?php _e('Door Price', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="doorprice" name="doorprice" type="text" style="width: 95%" value="<?php echo esc_attr($item['doorprice'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="guestlistprice"><?php _e('Guestlist Price', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="guestlistprice" name="guestlistprice" type="text" style="width: 95%" value="<?php echo esc_attr($item['guestlistprice'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>">
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="bookedtableprice"><?php _e('Booked Table Price', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="bookedtableprice" name="bookedtableprice" type="text" style="width: 95%" value="<?php echo esc_attr($item['bookedtableprice'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>">
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="latenight"><?php _e('Late Night Event', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="latenight" name="latenight" type="text" style="width: 95%" value="<?php echo esc_attr($item['latenight'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>">
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="agerange"><?php _e('Age Range', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="agerange" name="agerange" type="text" style="width: 95%" value="<?php echo esc_attr($item['agerange'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>">
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="dresscode"><?php _e('Dress Code', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="dresscode" name="dresscode" type="text" style="width: 95%" value="<?php echo esc_attr($item['dresscode'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="description"><?php _e('Description of The Event', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="description" name="description" type="text" style="width: 95%" value="<?php echo esc_attr($item['description'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>">
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="address"><?php _e('Address of The Event', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="address" name="address" type="text" style="width: 95%" value="<?php echo esc_attr($item['address'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>">
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="postcode"><?php _e('Postcode of The Event', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="postcode" name="postcode" type="text" style="width: 95%" value="<?php echo esc_attr($item['postcode'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="image"><?php _e('Flyer Upload', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="image" name="image" type="text" style="width: 95%" value="<?php echo esc_attr($item['image'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
    </tbody>
</table>
<?php
}

/**
 * Simple function that validates data and retrieve bool on success
 * and error message(s) on error
 *
 * @param $item
 * @return bool|string
 */
function custom_table_wtpa_validate_person($item)
{
    $messages = array();

    if (empty($item['eventname'])) $messages[] = __('Name is required', 'custom_table_wtpa');
    if (!empty($item['contactemail']) && !is_email($item['contactemail'])) $messages[] = __('E-Mail is in wrong format', 'custom_table_wtpa');
    if (!ctype_digit($item['agerange'])) $messages[] = __('Age Range in wrong format', 'custom_table_wtpa');
    //if(!empty($item['agerange']) && !absint(intval($item['age'])))  $messages[] = __('Age can not be less than zero');
    //if(!empty($item['agerange']) && !preg_match('/[0-9]+/', $item['age'])) $messages[] = __('Age must be number');
    //...

    if (empty($messages)) return true;
    return implode('<br />', $messages);
}

/**
 * Do not forget about translating your plugin, use __('english string', 'your_uniq_plugin_name') to retrieve translated string
 * and _e('english string', 'your_uniq_plugin_name') to echo it
 * in this example plugin your_uniq_plugin_name == custom_table_wtpa
 *
 * to create translation file, use poedit FileNew catalog...
 * Fill name of project, add "." to path (ENSURE that it was added - must be in list)
 * and on last tab add "__" and "_e"
 *
 * Name your file like this: [my_plugin]-[ru_RU].po
 *
 * http://codex.wordpress.org/Writing_a_Plugin#Internationalizing_Your_Plugin
 * http://codex.wordpress.org/I18n_for_WordPress_Developers
 */
function custom_table_wtpa_languages()
{
    load_plugin_textdomain('custom_table_wtpa', false, dirname(plugin_basename(__FILE__)));
}

add_action('init', 'custom_table_wtpa_languages');

/**
* Query database in a function which is included in below add shortcodes
*/

function wptashow() { 
?>
<table border="1">
<tr>
 <th>eventname</th>
 <th>contactemail</th>
 <th>agerange</th>
</tr>
  <?php
    global $wpdb;
    $result = $wpdb->get_results ( "SELECT * FROM wp_wherespartyapp" );
    foreach ( $result as $print )   {
    ?>
    <tr>
    <td><?php echo $print->eventname;?></td>
    <td><?php echo $print->contactemail;?></td>
    <td><?php echo $print->agerange;?></td>
    </tr>
        <?php }
?>  </table> <?php
}

/**
* Adds Shortcodes to site
*/

add_shortcode( 'wheresthepartyappaddevent', 'custom_table_wtpa_persons_form_meta_box_handler' );

add_shortcode( 'wheresthepartyappshowevent', 'wptashow');

/**
* PHP Include
*/

include '/Frontend/frontend.php';
?>
<?php 

function multipage_form($item){

global $wpdb;
        $page	=	$_POST['page'];
        $this_page	=	$_SERVER['REQUEST_URI'];
        $table_wtpa = $wpdb->prefix . 'wherespartyapp'; // do not forget about tables prefix
$data = array(                
                'id' => 0,
        'eventname' => $_POST['eventname'],
        'dateofevent' => $_POST['dateofevent'],
        'starttime' => $_POST['starttime'],
        'finishtime' => $_POST['finishtime'],
        'contactemail' => $_POST['contactemail'],
        'contactnumber' => $_POST['contactnumber'],
        'genre' => $_POST['genre'],
        'doorprice' => $_POST['doorprice'],
        'guestlistprice' => $_POST['guestlistprice'],
        'bookedtableprice' => $_POST['bookedtableprice'],
        'latenight' => $_POST['latenight'],
        'agerange' => $_POST['agerange'],
        'dresscode' => $_POST['dresscode'],
        'description' => $_POST['description'],
        'address' => $_POST['address'],
        'postcode' => $_POST['postcode'],
        'image' => $_POST['image'] 
            );
            $format = array(
                '%s',
                '%s'
            );
            $success=$wpdb->insert( $table_wtpa, $data, $format );
            

if ( $page == NULL )
     {
       ?>
       <form id="form" action="" method="POST">

<input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
        <?php /* NOTICE: here we storing id to determine will be item added or updated */ ?>
        <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>


<div class="metabox-holder" id="poststuff">
            <div id="post-body">
                <div id="post-body-content">

<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="eventname"><?php _e('Event Name', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="eventname" name="eventname" type="text" style="width: 95%" value="<?php echo esc_attr($item['eventname'])?>"
                   size="50" class="code" placeholder="<?php _e('Event name', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="dateofevent"><?php _e('Date of Event', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="dateofevent" name="dateofevent" type="text" style="width: 95%" value="<?php echo esc_attr($item['dateofevent'])?>"
                   size="50" class="code" placeholder="<?php _e('DD/MM/YYYY', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="starttime"><?php _e('Start Time', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="starttime" name="starttime" type="text" style="width: 95%" value="<?php echo esc_attr($item['starttime'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="finishtime"><?php _e('Finish Time', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="finishtime" name="finishtime" type="text" style="width: 95%" value="<?php echo esc_attr($item['finishtime'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="contactemail"><?php _e('Contact E-mail', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="contactemail" name="contactemail" type="text" style="width: 95%" value="<?php echo esc_attr($item['contactemail'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="contactnumber"><?php _e('Contact Number', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="contactnumber" name="contactnumber" type="text" style="width: 95%" value="<?php echo esc_attr($item['contactnumber'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="genre"><?php _e('Genre', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="genre" name="genre" type="text" style="width: 95%" value="<?php echo esc_attr($item['genre'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="doorprice"><?php _e('Door Price', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="doorprice" name="doorprice" type="text" style="width: 95%" value="<?php echo esc_attr($item['doorprice'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="guestlistprice"><?php _e('Guestlist Price', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="guestlistprice" name="guestlistprice" type="text" style="width: 95%" value="<?php echo esc_attr($item['guestlistprice'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>">
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="bookedtableprice"><?php _e('Booked Table Price', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="bookedtableprice" name="bookedtableprice" type="text" style="width: 95%" value="<?php echo esc_attr($item['bookedtableprice'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>">
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="latenight"><?php _e('Late Night Event', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="latenight" name="latenight" type="text" style="width: 95%" value="<?php echo esc_attr($item['latenight'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>">
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="agerange"><?php _e('Age Range', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="agerange" name="agerange" type="text" style="width: 95%" value="<?php echo esc_attr($item['agerange'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>">
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="dresscode"><?php _e('Dress Code', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="dresscode" name="dresscode" type="text" style="width: 95%" value="<?php echo esc_attr($item['dresscode'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="description"><?php _e('Description of The Event', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="description" name="description" type="text" style="width: 95%" value="<?php echo esc_attr($item['description'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>">
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="address"><?php _e('Address of The Event', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="address" name="address" type="text" style="width: 95%" value="<?php echo esc_attr($item['address'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>">
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="postcode"><?php _e('Postcode of The Event', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="postcode" name="postcode" type="text" style="width: 95%" value="<?php echo esc_attr($item['postcode'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
	<tr class="form-field">
        <th valign="top" scope="row">
            <label for="image"><?php _e('Flyer Upload', 'custom_table_wtpa')?></label>
        </th>
        <td>
            <input id="image" name="image" type="text" style="width: 95%" value="<?php echo esc_attr($item['image'])?>"
                   size="50" class="code" placeholder="<?php _e('', 'custom_table_wtpa')?>" >
        </td>
    </tr>
    </tbody>
</table>
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
        <?php /* NOTICE: here we storing id to determine will be item added or updated */ ?>
        <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>
                   

        
              <input type="hidden" value="1" name="page" />
                    <input type="submit" value="<?php _e('Save', 'custom_table_wtpa')?>" id="submit" class="button-primary" name="submit">
               
                </div>
            </div>
        </div>
    </form>





       <?php }  
       
       //End Page 1 of Form
elseif ( $page == 1 ) {
?>
<h3>Payments</h3>
<script src="https://www.paypalobjects.com/js/external/dg.js" type="text/javascript"></script>
<form action="https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay" target="PPDGFrame" class="standard">
<label for="buy">Buy Now:</label>
<input type="image" id="submitBtn" value="Pay with PayPal" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif">
<input id="type" type="hidden" name="expType" value="light">
<input id="paykey" type="hidden" name="paykey" value="insert_pay_key">
</form>

<script type="text/javascript" charset="utf-8">
var embeddedPPFlow = new PAYPAL.apps.DGFlow({trigger: 'submitBtn'});
</script>
</body>

<?php

$first_name	=	$_POST['first_name'];
$last_name	=	$_POST['last_name'];
$email	=	$_POST['email'];
$phone	=	$_POST['phone'];
$zip_code	=	$_POST['zip_code'];
echo "<h3>You made it to the 2nd page!</h3>
<p>Here are your form inputs: </p>
<p>First Name: ' . $first_name . '</p>
<p>Last Name: ' . $last_name . '</p>
<p>Email: ' . $email . '</p>
<p>Phone: ' . $phone . '</p>
";
?>
<form method="post" action=" <?php ' . $this_page .' ?> ">
<input type="hidden" value="2" name="page" />
                    <input type="submit" />
                    </form>
                    <?php
}//End Page 2 of Form
elseif ( $page == 2 ) {
?>
<h3>success</h3>


<?php
}
}


add_shortcode ( 'multipageformsc', 'multipage_form' );


?>

 