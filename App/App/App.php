<?php
class App
{
    private static $pdo;

    private $xmlParser;
    private $invocationScript;

    public static function init ($pathToApp, array $arguments, PDO $pdo)
    {
        self::$pdo = $pdo;

        $app = new App($pathToApp);
        $app->run($arguments);
    }

    private function __construct ($pathToApp)
    {
        $this->invocationScript = $pathToApp;
        $this->xmlParser = new XMLParser();
    }

    private function run ($arguments)
    {
        $command = array_shift($arguments);

        switch (strtolower($command)) {
            case 'import':
                $this->import($arguments[0]);
                break;
            
            default:
                $this->commandHelp();
                break;
        }
    }

    private function import($dataFilePath)
    {
        $dataObject = $this->xmlParser->parseFile($dataFilePath);

        $sql = new TemplatedSQL(self::$pdo);

        $sql->execute($dataObject);

        echo "Done. Inserted $dataFilePath rows.\n";
    }

    private function commandHelp ()
    {
        echo "Usage: {$this->invocationScript} <COMMAND> <PATH TO FILE>\n";
        echo "\n";
        echo "Commands:\n";
        echo "  import <path/to/data.xml>   Import the XML file data into your SQL database.\n";
    }
}
?>