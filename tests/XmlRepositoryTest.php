<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.03.15
 * Time: 15:37
 */

require_once(realpath(dirname(__FILE__) . '/..') . '/App/BookRepository.php');
require_once(realpath(dirname(__FILE__) . '/..') . '/App/Book.php');
require_once(realpath(dirname(__FILE__) . '/..') . '/App/Fields.php');
require_once(realpath(dirname(__FILE__) . '/..') . '/App/ReferenceFieldMultiple.php');
require_once(realpath(dirname(__FILE__) . '/..') . '/App/StringField.php');
require_once(realpath(dirname(__FILE__) . '/..') . '/App/PrimaryField.php');
require_once(realpath(dirname(__FILE__) . '/..') . '/App/MappedSQL.php');
require_once(realpath(dirname(__FILE__) . '/..') . '/App/AggregateField.php');
require_once(realpath(dirname(__FILE__) . '/..') . '/App/BoolField.php');
require_once(realpath(dirname(__FILE__) . '/..') . '/App/NumericField.php');
require_once(realpath(dirname(__FILE__) . '/..') . '/App/CLIMessage.php');



class XmlRepositoryTest extends PHPUnit_Framework_TestCase {

    protected $dir = '';
    protected $pdo;

    public function setUp() {

        $content = <<<EOL
<?xml version="1.0" encoding="UTF-8"?>
<books>
  <book id="10">
    <title>War and peace</title>
  </book>
  <book id="11">
    <title>Anna Karenina</title>
  </book>
</books>
EOL;

        $this->dir = "/tmp/" . md5(time());
        file_put_contents($this->dir . "books.xml", $content);

        global $CONFIG;

        $dsn = "mysql:dbname={$CONFIG['db_name']};host={$CONFIG['db_host']}";
        $pdo = new PDO($dsn, $CONFIG['db_user'], $CONFIG['db_pass'], array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql ="CREATE TABLE IF NOT EXISTS `books` (
              `id` INT NOT NULL,
              `title` VARCHAR(45) NULL,
              PRIMARY KEY (`id`));";
        $pdo->exec($sql);

        $this->pdo = $pdo;
    }

    public function testBookReps() {
        $book_repository = new BookRepository($this->dir . "books.xml", "books");

        $book1 = $book_repository->fetchNext();
        $book2 = $book_repository->fetchNext();

        $this->assertEquals('Book', get_class($book1));
        $this->assertEquals('10', $book1->id);
        $this->assertEquals('War and peace', $book1->title);
        $this->assertEquals('11', $book2->id);
        $this->assertEquals('Anna Karenina', $book2->title);

        $book_repository->apply();
        $sql = $this->pdo->prepare("SELECT * FROM book WHERE id = '10'");
        $sql->execute();
        $row = $sql->fetch();
        $this->assertEquals('War and peace', $row['title']);

    }


    public function tearDown() {
        unlink($this->dir."books.xml");
        $this->pdo->exec("DROP TABLE books");
    }

}