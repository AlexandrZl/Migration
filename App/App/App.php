<?php
class App
{
    private static $pdo;

    private $pathToFile;
    private $xmlParser;

    public static function init (array $arguments, PDO $pdo)
    {
        self::$pdo = $pdo;

        $app = new App($arguments);
        $app->run();
    }

    private function __construct ($arguments)
    {
        $this->pathToFile = $arguments;
        $this->xmlParser = new XMLParser();
    }

    private function run ()
    {
        $command = array_shift($this->pathToFile);

        $this->import($command);
    }

    private function import($dataFilePath)
    {
        $dataObject = $this->xmlParser->parseFile($dataFilePath);

        echo "Done. Inserted $dataFilePath rows.\n";
    }
}
?>