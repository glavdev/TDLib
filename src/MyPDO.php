<?php

final class MyPDO extends PDO
{
    private $config;

    public function __construct(array $config = null)
    {
        $this->config = $config ?? (array)json_decode(file_get_contents("./config.json"));
    }

    public function pdo()
    {
        static $cachedPDO;
        if ($cachedPDO) {
            return $cachedPDO;
        }

        $cachedPDO = new PDO(
            "mysql:dbname={$this->config['dbname']};host={$this->config['dbhost']}",
            $this->config['dbuser'],
            $this->config['dbpass']
        );


        $cachedPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $cachedPDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->exec('SET NAMES utf8');
        return $cachedPDO;
    }

    public function beginTransaction(): bool
    {
        return $this->pdo()->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo()->commit();
    }

    public function errorCode() : string
    {
        return $this->pdo()->errorCode();
    }

    public function errorInfo() : array
    {
        return $this->pdo()->errorInfo();
    }

    public function exec($query) : int
    {
        return $this->pdo()->exec($query);
    }

    public function getAttribute($attribute)
    {
        return $this->pdo()->getAttribute($attribute);
    }

    public function inTransaction() : bool
    {
        return $this->pdo()->inTransaction();
    }

    public function lastInsertId($seqname = null): string
    {
        return $this->pdo()->lastInsertId($seqname);
    }

    public function prepare($statement, $driverOptions = null): PDOStatement
    {
        return $this->pdo()->prepare($statement, $driverOptions);
    }

    public function query(string $statement) : PDOStatement
    {
        return $this->pdo()->query($statement);
    }

    public function quote($string, $paramtype = null)
    {
        return $this->pdo()->quote($string, $paramtype);
    }

    public function rollBack() : bool
    {
        return $this->pdo()->rollBack();
    }

    public function setAttribute($attribute, $value) : bool
    {
        return $this->pdo()->setAttribute($attribute, $value);
    }
}
