<?php
class Math
{
    protected $x;
    protected $result;

    public function __construct($x)
    {
        $this->x = (int) $x;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function calculate() {
        return $this->execute();
    }

    protected function execute()
    {
        if ($this->x < 0) {
            $this->result = $this->x * $this->x;
            return $this->getResult();

        } else if ($this->x <= 100 && $this->x >= 0) {
            $this->result = $this->x * 100;
            return $this->getResult();

        } else if ($this->x > 100) {
            $this->result = $this->x + (500 * $this->x);
            return $this->getResult();
        }
    }
}