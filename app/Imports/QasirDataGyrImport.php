<?php

namespace App\Imports;

use App\Models\QasirData;
use Maatwebsite\Excel\Concerns\ToModel;

class QasirDataGyrImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new QasirData([
           'nama_produk'     => $row[0],
           'variasi'    => $row[4], 
           'harga'    => $row[6], 
           'stok'    => $row[8], 
           'outlet'    => "gyr", 
        ]);
    }
}
