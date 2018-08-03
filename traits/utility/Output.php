<?php
namespace cli\traits\utility;

trait Output
{

    public function printArray(array $var, string $header = null, int $length = 30): string
    {
        $result = $this->header($header);
        foreach ($var as $key => $data) {
            if (is_array($data)) {
                $result .= $key . "\n";
                $result .= str_replace("\n", "\n\t", $this->printArray($data));
                $result .= str_repeat("_", $length) . "\n";
            } else {
                $result .= str_pad($key, $length) . ":\t" . $data . "\n";
            }
        }
        
        return $result;
    }

    public function print(string $var, string $header = null): string
    {
        $result = $this->header($header);
        $result .= $var . "\n";
        
        return $result;
    }

    private function header(?string $header): string
    {
        if ($header !== null) {
            $result = $header . "\n\n";
        } else {
            $result = '';
        }
        return $result;
    }
}