Contributing
============

If you want to contribute to Metatron, you're invited to fork the
`repository <https://github.com/s9y/Metatron>`__ and open a `Pull
Request <https://help.github.com/articles/using-pull-requests>`__.

-  Make sure you have `Composer <http://getcomposer.org/>`__ and
   `Phing <http://www.phing.info/>`__ installed
-  Clone the repository into the Serendipity web root, e.g.
   ``git clone https://github.com/s9y/Metatron.git``
-  Next, ``cd Metatron && composer install --dev`` to install the
   dependencies
-  You should always create a new version of the ``metatron.phar`` PHAR
   archive after you finished by simply calling ``phing`` (make sure
   that ``phar.readonly = Off`` in your php.ini)
-  To run commands from the Serendipity web root, either symlink the
   ``metatron.phar`` PHAR archive or call
   ``php Metatron/console.php [options] command [arguments]`` as root

If you find a bug in Metatron, please `file an
issue <https://github.com/s9y/Metatron/issues>`__. Metatron is currently
in an alpha state and should **not** be used on production servers! You
have been warned ;)

If you have any further questions, feel free to visit the `Serendipity
forum <http://board.s9y.org/>`__.
