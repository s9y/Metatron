Commands
========

Diagnostics
-----------

Serendipity version
^^^^^^^^^^^^^^^^^^^

Prints the current S9y version.

.. code:: bash

    $ php metatron.phar diag:version

Information about the current installation
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Prints basic information about the current S9y installation.

.. code:: bash

    $ php metatron.phar diag:info

Retrieve S9y config values
^^^^^^^^^^^^^^^^^^^^^^^^^^

Prints the value of one or more Serendipity config keys where the
optional argument ``name`` is the name of the config key. Setting the
``search`` option list all config keys that contain ``name``.

.. code:: bash

    $ php metatron.phar diag:config [-s|--search] [name]

User
----

List users
^^^^^^^^^^

Lists all users in the system. You can enter an optional ``username``.

.. code:: bash

    $ php metatron.phar user:list [username]

Set new password for user
^^^^^^^^^^^^^^^^^^^^^^^^^

Set new password for user. Opens a dialog where you must enter the
user's new password twice.

.. code:: bash

    $ php metatron.phar user:password username

Cache
-----

Flush cache
^^^^^^^^^^^

Flushes cache directory. You can enter an optional cache directory
(Default: templates\_c).

.. code:: bash

    $ php metatron.phar cache:flush [dir]

Comments
--------

List comments
^^^^^^^^^^^^^

Lists all comments, or the last X comments.

.. code:: bash

    $ php metatron.phar comment:list [limit]

Approve comments
^^^^^^^^^^^^^^^^

List and approve comments, or approve a single comment by id. This will
most certainly change in future versions.

.. code:: bash

    $ php metatron.phar comment:approve [commentid]

Plugins
-------

List plugins
^^^^^^^^^^^^

Lists installed plugins, optional types are 'event' and 'sidebar'.

.. code:: bash

    $ php metatron.phar plugin:list [type]

Metatron configuration
----------------------

As of version 0.2.0, Metatron will save certain settings in a
configuration file ``metatron_config.yml``.

Setting config values
^^^^^^^^^^^^^^^^^^^^^

Sets a value for a specific key.

.. code:: bash

    $ php metatron.phar config:set key value

Backup
------

Database
^^^^^^^^

Dump database
'''''''''''''

Creates a dump of the blog's database, schema only or full with data.
Optionally gzipped. Requires a backup directory to be set first.

.. code:: bash

    $ php metatron.phar backup:db:dump [--type[="..."]] [--gzipped]

