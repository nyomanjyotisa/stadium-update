<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Imports\QasirDataGyrImport;
use App\Exports\BerkasExport;

class ShopeeController extends Controller 
{

    public function update_stok(){
        
        // baca input user file export qasir gyr
        $path1 = request()->file('qasir_gyr')->store('temp'); 
        $path = storage_path('app').'/'.$path1;  
        $arrayGyr = Excel::toArray(new QasirDataGyrImport,$path);

        $resultArray = array();
            
        // dd($arrayGyr[0]);

        foreach($arrayGyr[0] as $singleGyr){
            if(str_contains($singleGyr[4], 'off') || str_contains($singleGyr[4], 'Nama Variasi') || is_null($singleGyr[9]) || $singleGyr[8] < 1){
                continue;
            }

            if($singleGyr[4] == null){
                $singleGyr[4] = 'gyr';
            }else{
                $singleGyr[4] = $singleGyr[4] . ' gyr';
            }

            $singleGyr[] = $singleGyr[0] . $singleGyr[4];
            $singleGyrArray = array($singleGyr[0], $singleGyr[4], $singleGyr[6], $singleGyr[8], $singleGyr[10]);

            // dd($singleGyrArray);

            $resultArray[] = $singleGyrArray;
        }

        // baca input user file export qasir dps
        $pathDps = request()->file('qasir_dps')->store('temp'); 
        $path2Dps = storage_path('app').'/'.$pathDps;  
        $arrayDps = Excel::toArray(new QasirDataGyrImport,$path2Dps);

        foreach($arrayDps[0] as $singleDps){
            if(str_contains($singleDps[4], 'off') || str_contains($singleDps[4], 'Nama Variasi') || is_null($singleGyr[9]) || $singleGyr[8] < 1){
                continue;
            }

            if($singleDps[4] == null){
                $singleDps[4] = 'dps';
            }else{
                $singleDps[4] = $singleDps[4] . ' dps';
            }

            $singleDps[] = $singleDps[0] . $singleDps[4];
            $singleDpsArray = array($singleDps[0], $singleDps[4], $singleDps[6], $singleDps[8], $singleDps[10]);

            $resultArray[] = $singleDpsArray;
        }
     
        // baca input produk shopee
        $pathShopee1 = request()->file('shopee1')->store('temp'); 
        $path2Shopee1 = storage_path('app').'/'.$pathShopee1;  
        $arrayShopee1 = Excel::toArray(new QasirDataGyrImport,$path2Shopee1);

        $resultShopee = array();


        foreach($arrayShopee1[0] as $singleData){
            if(str_contains($singleData[0], 'et_title_product_id') || str_contains($singleData[0], 'sales_info') || $singleData[0] == null || $singleData[0] == 'Kode Produk'){
                continue;
            }

            $i = 0;
            $singleData[3] = ltrim($singleData[3]); //remove whitescape from beginning
            $singleData[8] = $singleData[1] . $singleData[3];

            foreach ($resultArray as $cekData){
                if($cekData[4] == $singleData[8]){
                    $i = $i + 1;
                    if($cekData[3] != $singleData[7]){
                        $singleData[7] = $cekData[3];
                        if($cekData[3] == 0){
                            $singleData[7] = '0';
                        }
                        $resultShopee[] = $singleData;
                        break;
                    }
                }                
            }
            if($i == 0 && $singleData[7]){
                $singleData[7] = '0';
                $resultShopee[] = $singleData;
            }
        }

        $export = new BerkasExport([
            $resultShopee
        ]);
    
        return Excel::download($export, 'UpdateShopee.xlsx');
    }

    public function variasi_baru(){

        //Qasir GYR
        $path1 = request()->file('qasir_gyr')->store('temp'); 
        $path = storage_path('app').'/'.$path1;  
        $arrayGyr = Excel::toArray(new QasirDataGyrImport,$path);

        $resultArray = array();

        foreach($arrayGyr[0] as $singleGyr){
            if(str_contains($singleGyr[4], 'off') || str_contains($singleGyr[4], 'Nama Variasi')|| is_null($singleGyr[9]) || $singleGyr[8] < 1 ){
                continue;
            }
            if($singleGyr[4] == null){
                $singleGyr[4] = 'gyr';
            }else{
                $singleGyr[4] = $singleGyr[4] . ' gyr';
            }
            $singleGyr[] = $singleGyr[0] . $singleGyr[4];
            $singleGyrArray = array($singleGyr[0], $singleGyr[4], $singleGyr[6], $singleGyr[8], $singleGyr[10]);

            $resultArray[] = $singleGyrArray;
        }

        //Qasir DPS
        $pathDps = request()->file('qasir_dps')->store('temp'); 
        $path2Dps = storage_path('app').'/'.$pathDps;  
        $arrayDps = Excel::toArray(new QasirDataGyrImport,$path2Dps);

        foreach($arrayDps[0] as $singleDps){
            if(str_contains($singleDps[4], 'off') || str_contains($singleDps[4], 'Nama Variasi')|| is_null($singleGyr[9]) || $singleDps[8] < 1 ){
                continue;
            }
            if($singleDps[4] == null){
                $singleDps[4] = 'dps';
            }else{
                $singleDps[4] = $singleDps[4] . ' dps';
            }
            $singleDps[] = $singleDps[0] . $singleDps[4];
            $singleDpsArray = array($singleDps[0], $singleDps[4], $singleDps[6], $singleDps[8], $singleDps[10]);

            $resultArray[] = $singleDpsArray;
        }     

        $pathShopee1 = request()->file('shopee1')->store('temp'); 
        $path2Shopee1 = storage_path('app').'/'.$pathShopee1;  
        $arrayShopee1 = Excel::toArray(new QasirDataGyrImport,$path2Shopee1);

        $allArrayShopee = $arrayShopee1;

        // dd(count($allArrayShopee));

        foreach($resultArray as $singleValidQasir){
            foreach($allArrayShopee[0] as $singleDataAllShopee){
                $singleDataAllShopee[8] = $singleDataAllShopee[1] . ltrim($singleDataAllShopee[3]);
                if($singleValidQasir[4] == $singleDataAllShopee[8] || str_contains($singleValidQasir[4], 'off')){
                    $keyToDelete = array_search($singleValidQasir, $resultArray);
                    unset($resultArray[$keyToDelete]);
                    break;
                }
            }
        }

        // dd($resultArray);
        $copyResultArray = array();

        foreach($resultArray as $singleValidQasir){
            foreach($allArrayShopee[0] as $singleDataAllShopee){
                if($singleValidQasir[0] == $singleDataAllShopee[1]){
                    $copyResultArray[] = $singleValidQasir;
                    $keyToDelete = array_search($singleValidQasir, $resultArray);
                    unset($resultArray[$keyToDelete]);
                    break;
                }
            }
        }

        $exportNewVariantFromQasir = new BerkasExport([
            $copyResultArray
        ]);
    
        return Excel::download($exportNewVariantFromQasir, 'NewVariantFromQasir.xlsx');
    }

    public function produk_baru(){
        $path1 = request()->file('qasir_gyr')->store('temp'); 
        $path = storage_path('app').'/'.$path1;  
        $arrayGyr = Excel::toArray(new QasirDataGyrImport,$path);

        $resultArray = array();

        foreach($arrayGyr[0] as $singleGyr){
            if(str_contains($singleGyr[4], 'off') || str_contains($singleGyr[4], 'Nama Variasi') || $singleGyr[8] < 1 || is_null($singleGyr[9]) || str_contains($singleGyr[0], 'minuman') || str_contains($singleGyr[0], 'kaos kaki bola original')){
                continue;
            }
            if($singleGyr[4] == null){
                $singleGyr[4] = 'gyr';
            }else{
                $singleGyr[4] = $singleGyr[4] . ' gyr';
            }
            $singleGyr[] = $singleGyr[0] . $singleGyr[4];
            $singleGyrArray = array($singleGyr[0], $singleGyr[4], $singleGyr[6], $singleGyr[8], $singleGyr[10]);

            $resultArray[] = $singleGyrArray;
        }

        $pathDps = request()->file('qasir_dps')->store('temp'); 
        $path2Dps = storage_path('app').'/'.$pathDps;  
        $arrayDps = Excel::toArray(new QasirDataGyrImport,$path2Dps);

        foreach($arrayDps[0] as $singleDps){
            if(str_contains($singleDps[4], 'off') || str_contains($singleDps[4], 'Nama Variasi') || $singleDps[8] < 1 || is_null($singleGyr[9]) || str_contains($singleGyr[0], 'minuman') || str_contains($singleGyr[0], 'kaos kaki bola original')){
                continue;
            }
            if($singleDps[4] == null){
                $singleDps[4] = 'dps';
            }else{
                $singleDps[4] = $singleDps[4] . ' dps';
            }
            $singleDps[] = $singleDps[0] . $singleDps[4];
            $singleDpsArray = array($singleDps[0], $singleDps[4], $singleDps[6], $singleDps[8], $singleDps[10]);

            $resultArray[] = $singleDpsArray;
        }        

        $pathShopee1 = request()->file('shopee1')->store('temp'); 
        $path2Shopee1 = storage_path('app').'/'.$pathShopee1;  
        $arrayShopee1 = Excel::toArray(new QasirDataGyrImport,$path2Shopee1);

        $allArrayShopee = $arrayShopee1;

        foreach($resultArray as $singleValidQasir){
            foreach($allArrayShopee[0] as $singleDataAllShopee){
                $singleDataAllShopee[8] = $singleDataAllShopee[1] . ltrim($singleDataAllShopee[3]);
                if($singleValidQasir[4] == $singleDataAllShopee[8] || str_contains($singleValidQasir[4], 'off')){
                    $keyToDelete = array_search($singleValidQasir, $resultArray);
                    unset($resultArray[$keyToDelete]);
                    break;
                }
            }
        }

        // dd($resultArray);
        $copyResultArray = array();

        foreach($resultArray as $singleValidQasir){
            foreach($allArrayShopee[0] as $singleDataAllShopee){
                if($singleValidQasir[0] == $singleDataAllShopee[1]){
                    $copyResultArray[] = $singleValidQasir;
                    $keyToDelete = array_search($singleValidQasir, $resultArray);
                    unset($resultArray[$keyToDelete]);
                    break;
                }
            }
        }

        //add image
        $pathGambar = request()->file('gambar_qasir')->store('temp'); 
        $path2Gambar = storage_path('app').'/'.$pathGambar;  
        $arrayGambar = Excel::toArray(new QasirDataGyrImport,$path2Gambar);

        $endResultArray = array();

        // dd($arrayGambar);

        foreach($resultArray as $singleResult){
            foreach($arrayGambar[0] as $singleDataGambar){
                if($singleResult[0] == $singleDataGambar[1]){
                    $singleResult[] = $singleDataGambar[3];
                }
            }
            $endResultArray[] = $singleResult;
        }

        $exportNewFromQasir = new BerkasExport([
            $endResultArray
        ]);
    
        return Excel::download($exportNewFromQasir, 'NewFromQasir.xlsx');
    }
}