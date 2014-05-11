Usage
=====

Right now, there are only a few :doc:`commands <commands>`. Change to your Serendipity
root directory and make sure you have read permissions to
``serendipity_config_local.inc.php``.

You get a list of all available commands by entering:

.. code:: bash

    $ php metatron.phar list

If you need help running a command, type:

.. code:: bash

    $ php metatron.phar help <command>

Keeping Metatron up to date
---------------------------

As of version 0.1.1, Metatron is able to update itself to the latest
version. Just run

.. code:: bash

    $ php metatron.phar self-update
