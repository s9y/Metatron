.. Metatron documentation master file, created by
   sphinx-quickstart on Sun May 11 10:36:03 2014.
   You can adapt this file completely to your liking, but it should at least
   contain the root `toctree` directive.

Metatron – CLI tool for S9y
===========================

A command line tool (written in PHP) for developers and admins of
`Serendipity <http://s9y.org>`__.

Contents:

.. toctree::
   :maxdepth: 2

   self
   usage
   commands
   contributing
   testing
   credits
   license

.. _getting_started:

Installation
------------

There are two ways to install Metatron.

Install the metatron.phar file
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Download the phar file to your Serendipity root directory:

.. code:: bash

    $ wget https://raw.github.com/s9y/Metatron/master/metatron.phar

Install Metatron from source using Composer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: bash

    $ git clone https://github.com/s9y/Metatron # Clone the git repository
    $ cd Metatron
    $ curl -s https://getcomposer.org/installer | php # Download Composer
    $ php ./composer.phar install # Use Composer to install all dependencies

Requirements
~~~~~~~~~~~~

Metatron has been tested on Linux and PHP 5.4.9/5.5.3, but should run in
other environments and at least PHP 5.3.3.

If you use Suhosin, you should probably add/edit the following line to
its config (e.g. /etc/php5/cli/conf.d/suhosin.ini)
``suhosin.executor.include.whitelist = php phar``

Indices and tables
==================

* :ref:`genindex`
* :ref:`modindex`
* :ref:`search`
