wp-additional-editor-capabilities
=================================

Allow less technical clients to manage their Wordpress site without all the bloat in admin interface by giving them a slightly enhanced Editor/Shop Manager role.

This plugin grants the Editor role permissions to edit (non-admin) users and gives them access to Appearance menu items. 
For WooCommerce users, the Shop Manager role is granted access to Appearance menu as well.
This should be everything a non-technical client needs to manage their site, with a pleasantly clean and empty admin interface.

For even better results, combine with [WP-Cleanup](https://github.com/codelight-eu/wp-cleanup).

Note that this plugin has *not* been tested thoroughly - patches and issues are welcome.

Details
=======

By default, the Editor role is given the following capabilities:


```
'list_users',
'add_users',
'create_users',
'edit_users',
'promote_users',
'remove_users',
'delete_users',
'edit_theme_options'
```

To edit the given capabilities, use the _'cl_editor_capabilities'_ filter.

By default, the Shop Manager role is given the following capabilities:
```
'edit_theme_options'
```

To edit the given capabilities, use the _'cl_shop_manager_capabilities'_ filter.

Note that the shop manager / editor are not given the capability to delete users.
