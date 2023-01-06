<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Imports\QasirDataGyrImport;
use App\Exports\BerkasExport;

class TokopediaDpsController extends Controller 
{
    public function update_stok() 
    {
        $path1 = request()->file('qasir_dps')->store('temp'); 
        $path = storage_path('app').'/'.$path1;  
        $array = Excel::toArray(new QasirDataGyrImport,$path);

        $resultArray = array();
        // dd($array);

        foreach($array[0] as $single){
            if(str_contains($single[4], 'off') || str_contains($single[4], 'Nama Variasi')){
                continue;
            }
            if($single[4] != null){
                $single[] = $single[0] . ' - ' . $single[4];
                $singleArray = array($single[0], $single[4], $single[6], $single[8], $single[10]);
            }else{
                $singleArray = array($single[0], $single[4], $single[6], $single[8], $single[4]);
            }
            
            $resultArray[] = $singleArray;
        }

        // dd($resultArray);

        $pathTokped1 = request()->file('tokped1')->store('temp'); 
        $path2Tokped1 = storage_path('app').'/'.$pathTokped1;  
        $arrayTokped1 = Excel::toArray(new QasirDataGyrImport,$path2Tokped1);

        // dd($arrayTokped1);

        // dd($arrayTokped1[0][4]);

        $resultTokped = array();

        foreach($arrayTokped1[0] as $singleData){
            if($singleData[11] == null){
                continue;
            }
            if($singleData[0] == "Error Message"){
                continue;
            }
            if(str_contains($singleData[0], 'Abaikan kolom ini')){
                continue;
            }
            $i = 0;
            $singleData[1] = "$singleData[1]";
            foreach ($resultArray as $cekData){
                if(strtolower($cekData[4]) == strtolower($singleData[2])){
                    $i = $i + 1;
                    if($cekData[3] != $singleData[8]){
                        $singleData[8] = $cekData[3];
                        if($cekData[3] < 1){
                            $singleData[11] = 'Nonaktif';
                            $singleData[8] = '0';
                        }else{
                            $singleData[11] = 'Aktif';
                        }
                        foreach(explode('.',$singleData[1]) as $row){
                            $singleData[] = $row;
                        }
                        $resultTokped[] = $singleData;
                        break;
                    }
                }
            }
            if($i == 0 && $singleData[8]){
                $singleData[11] = 'Nonaktif';
                $singleData[8] = '0';
                foreach(explode('.',$singleData[1]) as $row){
                    $singleData[] = $row;
                }
                $resultTokped[] = $singleData;
            }
        }

        $pathTokped2 = request()->file('tokped2')->store('temp'); 
        $path2Tokped2 = storage_path('app').'/'.$pathTokped2;  
        $arrayTokped2 = Excel::toArray(new QasirDataGyrImport,$path2Tokped2);

        // dd($arrayTokped1);

        foreach($arrayTokped2[0] as $singleData){
            if($singleData[11] == null){
                continue;
            }
            if($singleData[0] == "Error Message"){
                continue;
            }
            if(str_contains($singleData[0], 'Abaikan kolom ini')){
                continue;
            }
            $i = 0;
            $singleData[1] = "$singleData[1]";
            foreach ($resultArray as $cekData){
                if(strtolower($cekData[4]) == strtolower($singleData[2])){
                    $i = $i + 1;
                    if($cekData[3] != $singleData[8]){
                        $singleData[8] = $cekData[3];
                        if($cekData[3] < 1){
                            $singleData[11] = 'Nonaktif';
                        }else{
                            $singleData[11] = 'Aktif';
                        }
                        foreach(explode('.',$singleData[1]) as $row){
                            $singleData[] = $row;
                        }
                        $resultTokped[] = $singleData;
                        break;
                    }
                }
            }
            if($i == 0 && $singleData[8]){
                $singleData[11] = 'Nonaktif';
                $singleData[8] = '0';
                foreach(explode('.',$singleData[1]) as $row){
                    $singleData[] = $row;
                }
                $resultTokped[] = $singleData;
            }
        }

        // dd($resultTokped);

        $export = new BerkasExport([
            $resultTokped
        ]);
    
        return Excel::download($export, 'UpdateTokpedDps.xlsx');
    }

    public function variasi_baru(){
        $path1 = request()->file('qasir_dps')->store('temp'); 
        $path = storage_path('app').'/'.$path1;  
        $array = Excel::toArray(new QasirDataGyrImport,$path);

        $resultArray = array();

        foreach($array[0] as $single){
            if(str_contains($single[4], 'off') || str_contains($single[4], 'Nama Variasi') || $single[8] < 1 ){
                continue;
            }
            if($single[4] != null){
                $single[] = $single[0] . ' - ' . $single[4];
                $singleArray = array($single[0], $single[4], $single[6], $single[8], $single[10]);
            }else{
                $singleArray = array($single[0], $single[4], $single[6], $single[8], $single[4]);
            }

            $resultArray[] = $singleArray;
        }

        // dd($resultArray);

        $pathTokped1 = request()->file('tokped1')->store('temp'); 
        $path2Tokped1 = storage_path('app').'/'.$pathTokped1;  
        $arrayTokped1 = Excel::toArray(new QasirDataGyrImport,$path2Tokped1);

        // dd($arrayTokped1);

        $resultTokped = array();

        foreach($arrayTokped1[0] as $singleData){
            if($singleData[11] == null){
                continue;
            }
            $i = 0;
            foreach ($resultArray as $cekData){
                if(strtolower($cekData[4]) == strtolower($singleData[2])){
                    $i = $i + 1;
                    if($cekData[3] != $singleData[8]){
                        $singleData[8] = $cekData[3];
                        if($cekData[3] < 1){
                            $singleData[11] = 'Nonaktif';
                            $singleData[8] = '0';
                        }else{
                            $singleData[11] = 'Aktif';
                        }
                        $resultTokped[] = $singleData;
                        break;
                    }
                }
            }
            if($i == 0 && $singleData[8]){
                $singleData[11] = 'Nonaktif';
                $singleData[8] = '0';
                $resultTokped[] = $singleData;
            }
        }

        $pathTokped2 = request()->file('tokped2')->store('temp'); 
        $path2Tokped2 = storage_path('app').'/'.$pathTokped2;  
        $arrayTokped2 = Excel::toArray(new QasirDataGyrImport,$path2Tokped2);

        // dd($arrayTokped1);

        foreach($arrayTokped2[0] as $singleData){
            if($singleData[11] == null){
                continue;
            }
            $i = 0;
            foreach ($resultArray as $cekData){
                if(strtolower($cekData[4]) == strtolower($singleData[2])){
                    $i = $i + 1;
                    if($cekData[3] != $singleData[8]){
                        $singleData[8] = $cekData[3];
                        if($cekData[3] < 1){
                            $singleData[11] = 'Nonaktif';
                        }else{
                            $singleData[11] = 'Aktif';
                        }
                        $resultTokped[] = $singleData;
                        break;
                    }
                }
            }
            if($i == 0 && $singleData[8]){
                $singleData[11] = 'Nonaktif';
                $singleData[8] = '0';
                $resultTokped[] = $singleData;
            }
        }

        // dd($resultTokped);

        $allArrayTokped = array_merge($arrayTokped1, $arrayTokped2);

        // dd($allArrayTokped);

        foreach($resultArray as $singleValidQasir){
            for ($x = 0; $x <= 1; $x++) {
                foreach($allArrayTokped[$x] as $singleDataAllTokped){
                    if(strtolower($singleValidQasir[4]) == strtolower($singleDataAllTokped[2]) || str_contains($singleValidQasir[4], 'off')){
                        $keyToDelete = array_search($singleValidQasir, $resultArray);
                        unset($resultArray[$keyToDelete]);
                        break;
                    }
                }
            }
        }

        // dd($resultArray);
        $copyResultArray = array();

        foreach($resultArray as $singleValidQasir){
            for ($x = 0; $x <= 1; $x++) {
                foreach($allArrayTokped[$x] as $singleDataAllTokped){
                    if($singleValidQasir[0] == explode(" - ",$singleDataAllTokped[2])[0]){
                        $copyResultArray[] = $singleValidQasir;
                        $keyToDelete = array_search($singleValidQasir, $resultArray);
                        unset($resultArray[$keyToDelete]);
                        break;
                    }
                }
            }
        }

        $export = new BerkasExport([
            $copyResultArray
        ]);
    
        return Excel::download($export, 'NewVariantTokpedDps.xlsx');
    }

    public function produk_baru(){
        $path1 = request()->file('qasir_dps')->store('temp'); 
        $path = storage_path('app').'/'.$path1;  
        $array = Excel::toArray(new QasirDataGyrImport,$path);

        $resultArray = array();

        foreach($array[0] as $single){
            if(str_contains($single[4], 'off') || str_contains($single[4], 'Nama Variasi') || $single[8] < 1 ){
                continue;
            }
            if($single[4] != null){
                $single[] = $single[0] . ' - ' . $single[4];
                $singleArray = array($single[0], $single[4], $single[6], $single[8], $single[10]);
            }else{
                $singleArray = array($single[0], $single[4], $single[6], $single[8], $single[4]);
            }

            $resultArray[] = $singleArray;
        }

        // dd($resultArray);

        $pathTokped1 = request()->file('tokped1')->store('temp'); 
        $path2Tokped1 = storage_path('app').'/'.$pathTokped1;  
        $arrayTokped1 = Excel::toArray(new QasirDataGyrImport,$path2Tokped1);

        // dd($arrayTokped1);

        $resultTokped = array();

        foreach($arrayTokped1[0] as $singleData){
            if($singleData[11] == null){
                continue;
            }
            $i = 0;
            foreach ($resultArray as $cekData){
                if(strtolower($cekData[4]) == strtolower($singleData[2])){
                    $i = $i + 1;
                    if($cekData[3] != $singleData[8]){
                        $singleData[8] = $cekData[3];
                        if($cekData[3] < 1){
                            $singleData[11] = 'Nonaktif';
                            $singleData[8] = '0';
                        }else{
                            $singleData[11] = 'Aktif';
                        }
                        $resultTokped[] = $singleData;
                        break;
                    }
                }
            }
            if($i == 0 && $singleData[8]){
                $singleData[11] = 'Nonaktif';
                $singleData[8] = '0';
                $resultTokped[] = $singleData;
            }
        }

        $pathTokped2 = request()->file('tokped2')->store('temp'); 
        $path2Tokped2 = storage_path('app').'/'.$pathTokped2;  
        $arrayTokped2 = Excel::toArray(new QasirDataGyrImport,$path2Tokped2);

        // dd($arrayTokped1);

        foreach($arrayTokped2[0] as $singleData){
            if($singleData[11] == null){
                continue;
            }
            $i = 0;
            foreach ($resultArray as $cekData){
                if(strtolower($cekData[4]) == strtolower($singleData[2])){
                    $i = $i + 1;
                    if($cekData[3] != $singleData[8]){
                        $singleData[8] = $cekData[3];
                        if($cekData[3] < 1){
                            $singleData[11] = 'Nonaktif';
                        }else{
                            $singleData[11] = 'Aktif';
                        }
                        $resultTokped[] = $singleData;
                        break;
                    }
                }
            }
            if($i == 0 && $singleData[8]){
                $singleData[11] = 'Nonaktif';
                $singleData[8] = '0';
                $resultTokped[] = $singleData;
            }
        }

        // dd($resultTokped);

        $allArrayTokped = array_merge($arrayTokped1, $arrayTokped2);

        // dd($allArrayTokped);

        foreach($resultArray as $singleValidQasir){
            for ($x = 0; $x <= 1; $x++) {
                foreach($allArrayTokped[$x] as $singleDataAllTokped){
                    if(strtolower($singleValidQasir[4]) == strtolower($singleDataAllTokped[2]) || str_contains($singleValidQasir[4], 'off')){
                        $keyToDelete = array_search($singleValidQasir, $resultArray);
                        unset($resultArray[$keyToDelete]);
                        break;
                    }
                }
            }
        }

        $pathMediaShopee = request()->file('media_shopee')->store('temp'); 
        $path2MediaShopee = storage_path('app').'/'.$pathMediaShopee;  
        $arrayMediaShopee = Excel::toArray(new QasirDataGyrImport,$path2MediaShopee);

        // dd($arrayMediaShopee[0][10]);

        $finishNewProductArray = array();

        foreach($resultArray as $singleValidQasir){
            foreach($arrayMediaShopee[0] as $singleDataMediaShopee){
                if(strtolower($singleValidQasir[0]) == strtolower($singleDataMediaShopee[2])){
                    $singleValidQasir[] = $singleDataMediaShopee[4];
                    $singleValidQasir[] = $singleDataMediaShopee[3];

                    $singleGyrArray = array($singleValidQasir[0], $singleValidQasir[1], $singleValidQasir[2], $singleValidQasir[3], $singleValidQasir[4], $singleValidQasir[5], $singleValidQasir[6]);

                    $finishNewProductArray[] = $singleGyrArray;
                    break;
                }
            }
        }

        $export = new BerkasExport([
            $finishNewProductArray
        ]);
    
        return Excel::download($export, 'NewProductTokpedDps.xlsx');
    }
}