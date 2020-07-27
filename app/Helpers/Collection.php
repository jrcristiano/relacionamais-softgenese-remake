<?php

namespace App\Helpers;

class Collection
{
    public function forEach(array $data, $field)
    {
        if (array_key_exists($field, $data)) {
            $fields = [];
            foreach ($data[$field] as $key => $value) {
                $fields[] = $value;
            }
            return $fields;
        }
        return null;
    }

    public function filter(array $data)
    {
        $filter = [];
        foreach ($data as $value) {
            if ($value['spreadsheet_bank'] == 341 && $value['spreadsheet_account'] == 'C') {
                $filter['itau'][] = $value;
            }

            if ($value['spreadsheet_bank'] == 341 && $value['spreadsheet_account'] == 'P') {
                $filter['itau_savings'][] = $value;
            }

            if ($value['spreadsheet_bank'] != 341) {
                $filter['others'][] = $value;
            }
        }

        return $filter;
    }
}
