<?php

namespace App\Lib;


class DatParser implements ParserInterface
{
    /**
     * @param $content
     * @return array
     */
    public function parse($content)
    {
        $parsedContent = array();
        $bills = explode("00:00:00", $content);
        foreach ($bills as $key => $bill) {
            if (!empty($bill)) {
                $billInfo = explode(';', $bill);

                $date = $this->extractPaymentDate($billInfo[0]);
                $amount = $this->extractAmount($billInfo[0]);
                $account = $this->extractAcount($billInfo[1]);
                $explanation = $this->extractExplanation($billInfo[3]);
                $name = $this->extractName($billInfo[2]);
                $surname = $this->extractSurname($billInfo[2]);
                $hash = md5($date . $amount . $account . $name . $surname);

                $parsedContent[$hash] = array(
                    'date' => $date,
                    'amount' => $amount,
                    'account' => $account,
                    'explanation' => $explanation,
                    'name' => $name,
                    'surname' => $surname,
                    'hash' => $hash,
                );
            }
        }

        return $parsedContent;
    }

    /**
     * @param string $data
     * @return mixed
     */
    private function extractPaymentDate(string $data)
    {
        $data = explode('|', $data);

        return $data[1];
    }

    /**
     * @param string $data
     * @return mixed
     */
    private function extractAmount(string $data)
    {
        $data = explode('|', $data);

        return $data[3];
    }

    /**
     * @param string $data
     * @return string
     */
    private function extractAcount(string $data)
    {
        $data = explode(':', $data);

        return trim($data[1]);
    }

    /**
     * @param string $data
     * @return string
     */
    private function extractExplanation(string $data)
    {
        $data = explode(':', $data);

        return trim($data[1]);
    }

    /**
     * @param string $data
     * @return string
     */
    private function extractName(string $data)
    {
        $data = explode(':', $data);
        $personInfo = explode(' ', trim($data[1]));

        return trim($personInfo[0]);
    }

    /**
     * @param string $data
     * @return string
     */
    private function extractSurname(string $data)
    {
        $data = explode(':', $data);
        $personInfo = explode(' ', trim($data[1]));

        return trim($personInfo[1]);
    }

}