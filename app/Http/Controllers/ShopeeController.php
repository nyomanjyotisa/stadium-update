<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Imports\QasirDataGyrImport;
use App\Exports\BerkasExport;

class ShopeeController extends Controller 
{
    public function import() 
    {
        $path1 = request()->file('qasir_gyr')->store('temp'); 
        $path = storage_path('app').'/'.$path1;  
        $arrayGyr = Excel::toArray(new QasirDataGyrImport,$path);

        $resultArray = array();

        foreach($arrayGyr[0] as $singleGyr){
            if(str_contains($singleGyr[4], 'off') || str_contains($singleGyr[4], 'Nama Variasi') || $singleGyr[8] < 1 ){
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
            if(str_contains($singleDps[4], 'off') || str_contains($singleDps[4], 'Nama Variasi') || $singleDps[8] < 1 ){
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

        $resultJson = json_encode($resultArray);
        
        // dd($resultJson);

        

        $pathShopee1 = request()->file('shopee1')->store('temp'); 
        $path2Shopee1 = storage_path('app').'/'.$pathShopee1;  
        $arrayShopee1 = Excel::toArray(new QasirDataGyrImport,$path2Shopee1);

        // dd($arrayShopee1);

        $resultShopee = array();

        foreach($arrayShopee1[0] as $singleData){
            if(str_contains($singleData[0], 'et_title_product_id') || str_contains($singleData[0], 'sales_info') || $singleData[0] == null || $singleData[0] == 'Kode Produk'){
                continue;
            }
            $singleData[3] = ltrim($singleData[3]);
            $singleData[] = $singleData[1] . $singleData[3];

            foreach ($resultArray as $cekData){
                if($cekData[4] == $singleData[26]){
                    if($cekData[3] != $singleData[7]){
                        $singleData[7] = $cekData[3];
                        $resultShopee[] = $singleData;
                        break;
                    }
                }
            }
        }

        $pathShopee2 = request()->file('shopee2')->store('temp'); 
        $path2Shopee2 = storage_path('app').'/'.$pathShopee2;  
        $arrayShopee2 = Excel::toArray(new QasirDataGyrImport,$path2Shopee2);

        foreach($arrayShopee2[0] as $singleData){
            if(str_contains($singleData[0], 'et_title_product_id') || str_contains($singleData[0], 'sales_info') || $singleData[0] == null || $singleData[0] == 'Kode Produk'){
                continue;
            }
            $singleData[3] = ltrim($singleData[3]);
            $singleData[] = $singleData[1] . $singleData[3];

            foreach ($resultArray as $cekData){
                if($cekData[4] == $singleData[26]){
                    if($cekData[3] != $singleData[7]){
                        $singleData[7] = $cekData[3];
                        $resultShopee[] = $singleData;
                        break;
                    }
                }
            }
        }


        $pathShopee3 = request()->file('shopee3')->store('temp'); 
        $path2Shopee3 = storage_path('app').'/'.$pathShopee3;  
        $arrayShopee3 = Excel::toArray(new QasirDataGyrImport,$path2Shopee3);
        
        foreach($arrayShopee3[0] as $singleData){
            if(str_contains($singleData[0], 'et_title_product_id') || str_contains($singleData[0], 'sales_info') || $singleData[0] == null || $singleData[0] == 'Kode Produk'){
                continue;
            }
            $singleData[3] = ltrim($singleData[3]);
            $singleData[] = $singleData[1] . $singleData[3];

            foreach ($resultArray as $cekData){
                if($cekData[4] == $singleData[26]){
                    if($cekData[3] != $singleData[7]){
                        $singleData[7] = $cekData[3];
                        $singleData[0] = $singleData[7] . '';
                        $singleData[2] = $singleData[2] . '';
                        $resultShopee[] = $singleData;
                        break;
                    }
                }
            }
        }

        $allArrayShopee = array_merge($arrayShopee1, $arrayShopee2, $arrayShopee3);

        // dd(count($allArrayShopee));

        foreach($resultArray as $singleValidQasir){
            for ($x = 0; $x <= 2; $x++) {
                foreach($allArrayShopee[$x] as $singleDataAllShopee){
                    $singleDataAllShopee[] = $singleDataAllShopee[1] . ltrim($singleDataAllShopee[3]);
                    if($singleValidQasir[4] == $singleDataAllShopee[26] || str_contains($singleValidQasir[4], 'off')){
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
            for ($x = 0; $x <= 2; $x++) {
                foreach($allArrayShopee[$x] as $singleDataAllShopee){
                    if($singleValidQasir[0] == $singleDataAllShopee[1]){
                        $copyResultArray[] = $singleValidQasir;
                        $keyToDelete = array_search($singleValidQasir, $resultArray);
                        unset($resultArray[$keyToDelete]);
                        break;
                    }
                }
            }
        }

        // $exportNewVariantFromQasir = new BerkasExport([
        //     $copyResultArray
        // ]);
    
        // return Excel::download($exportNewVariantFromQasir, 'NewVariantFromQasir.xlsx');

        // $exportNewFromQasir = new BerkasExport([
        //     $resultArray
        // ]);
    
        // return Excel::download($exportNewFromQasir, 'NewFromQasir.xlsx');

        $export = new BerkasExport([
            $resultShopee
        ]);
    
        return Excel::download($export, 'UpdateShopee.xlsx');
    }

    public function update_stok(){
        
        // baca input user file export qasir gyr
        $path1 = request()->file('qasir_gyr')->store('temp'); 
        $path = storage_path('app').'/'.$path1;  
        $arrayGyr = Excel::toArray(new QasirDataGyrImport,$path);

        $resultArray = array();

        foreach($arrayGyr[0] as $singleGyr){
            if(str_contains($singleGyr[4], 'off') || str_contains($singleGyr[4], 'Nama Variasi')){
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

        // baca input user file export qasir dps
        $pathDps = request()->file('qasir_dps')->store('temp'); 
        $path2Dps = storage_path('app').'/'.$pathDps;  
        $arrayDps = Excel::toArray(new QasirDataGyrImport,$path2Dps);

        foreach($arrayDps[0] as $singleDps){
            if(str_contains($singleDps[4], 'off') || str_contains($singleDps[4], 'Nama Variasi')){
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

        $resultShopee = array();

        foreach($arrayShopee1[0] as $singleData){
            if(str_contains($singleData[0], 'et_title_product_id') || str_contains($singleData[0], 'sales_info') || $singleData[0] == null || $singleData[0] == 'Kode Produk'){
                continue;
            }

            $i = 0;
            $singleData[3] = ltrim($singleData[3]);
            $singleData[] = $singleData[1] . $singleData[3];

            foreach ($resultArray as $cekData){
                if($cekData[4] == $singleData[26]){
                    $i = $i + 1;
                    if($cekData[3] != $singleData[7]){
                        $singleData[7] = $cekData[3];
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

        $pathShopee2 = request()->file('shopee2')->store('temp'); 
        $path2Shopee2 = storage_path('app').'/'.$pathShopee2;  
        $arrayShopee2 = Excel::toArray(new QasirDataGyrImport,$path2Shopee2);

        foreach($arrayShopee2[0] as $singleData){
            if(str_contains($singleData[0], 'et_title_product_id') || str_contains($singleData[0], 'sales_info') || $singleData[0] == null || $singleData[0] == 'Kode Produk'){
                continue;
            }

            $i = 0;
            $singleData[3] = ltrim($singleData[3]);
            $singleData[] = $singleData[1] . $singleData[3];

            foreach ($resultArray as $cekData){
                if($cekData[4] == $singleData[26]){
                    $i = $i + 1;
                    if($cekData[3] != $singleData[7]){
                        $singleData[7] = $cekData[3];
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

        if(request()->file('shopee3')){
            
        }

        // $pathShopee3 = request()->file('shopee3')->store('temp'); 
        // $path2Shopee3 = storage_path('app').'/'.$pathShopee3;  
        // $arrayShopee3 = Excel::toArray(new QasirDataGyrImport,$path2Shopee3);
        
        // foreach($arrayShopee3[0] as $singleData){
        //     if(str_contains($singleData[0], 'et_title_product_id') || str_contains($singleData[0], 'sales_info') || $singleData[0] == null || $singleData[0] == 'Kode Produk'){
        //         continue;
        //     }
        //     $singleData[3] = ltrim($singleData[3]);
        //     $singleData[] = $singleData[1] . $singleData[3];

        //     foreach ($resultArray as $cekData){
        //         if($cekData[4] == $singleData[26]){
        //             if($cekData[3] != $singleData[7]){
        //                 $singleData[7] = $cekData[3];
        //                 $resultShopee[] = $singleData;
        //                 break;
        //             }
        //         }
        //     }
        // }


        //penjualan shopee belum diproses
        $pathShopeePending = request()->file('shopee_pending')->store('temp'); 
        $path2ShopeePending = storage_path('app').'/'.$pathShopeePending;  
        $arrayShopeePending = Excel::toArray(new QasirDataGyrImport,$path2ShopeePending);

        // dd($resultShopee);

        foreach($arrayShopeePending[0] as $singleData){
            if(str_contains($singleData[0], 'No. Pesanan')){
                continue;
            }
            $singleData[13] = ltrim($singleData[13]);
            $singleData[] = $singleData[11] . $singleData[13];

            $ii = 0;
            foreach ($resultShopee as $cekData){
                if($cekData[26] == $singleData[46]){
                    $resultShopee[$ii][7] = $resultShopee[$ii][7] - $singleData[16];
                }
                
                if($cekData[7] < 1) {
                    $resultShopee[$ii][7] = '0';
                }

                $ii += 1;
            }
        }        

        $export = new BerkasExport([
            $resultShopee
        ]);
    
        return Excel::download($export, 'UpdateShopee.xlsx');
    }

    public function variasi_baru(){
        $path1 = request()->file('qasir_gyr')->store('temp'); 
        $path = storage_path('app').'/'.$path1;  
        $arrayGyr = Excel::toArray(new QasirDataGyrImport,$path);

        $resultArray = array();

        foreach($arrayGyr[0] as $singleGyr){
            if(str_contains($singleGyr[4], 'off') || str_contains($singleGyr[4], 'Nama Variasi') || $singleGyr[8] < 1 ){
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
            if(str_contains($singleDps[4], 'off') || str_contains($singleDps[4], 'Nama Variasi') || $singleDps[8] < 1 ){
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

        $resultJson = json_encode($resultArray);
        
        // dd($resultJson);

        

        $pathShopee1 = request()->file('shopee1')->store('temp'); 
        $path2Shopee1 = storage_path('app').'/'.$pathShopee1;  
        $arrayShopee1 = Excel::toArray(new QasirDataGyrImport,$path2Shopee1);

        // dd($arrayShopee1);

        $resultShopee = array();

        // foreach($arrayShopee1[0] as $singleData){
        //     if(str_contains($singleData[0], 'et_title_product_id') || str_contains($singleData[0], 'sales_info') || $singleData[0] == null || $singleData[0] == 'Kode Produk'){
        //         continue;
        //     }
        //     $singleData[3] = ltrim($singleData[3]);
        //     $singleData[] = $singleData[1] . $singleData[3];

        //     foreach ($resultArray as $cekData){
        //         if($cekData[4] == $singleData[26]){
        //             if($cekData[3] != $singleData[7]){
        //                 $singleData[7] = $cekData[3];
        //                 $resultShopee[] = $singleData;
        //                 break;
        //             }
        //         }
        //     }
        // }

        $pathShopee2 = request()->file('shopee2')->store('temp'); 
        $path2Shopee2 = storage_path('app').'/'.$pathShopee2;  
        $arrayShopee2 = Excel::toArray(new QasirDataGyrImport,$path2Shopee2);

        // foreach($arrayShopee2[0] as $singleData){
        //     if(str_contains($singleData[0], 'et_title_product_id') || str_contains($singleData[0], 'sales_info') || $singleData[0] == null || $singleData[0] == 'Kode Produk'){
        //         continue;
        //     }
        //     $singleData[3] = ltrim($singleData[3]);
        //     $singleData[] = $singleData[1] . $singleData[3];

        //     foreach ($resultArray as $cekData){
        //         if($cekData[4] == $singleData[26]){
        //             if($cekData[3] != $singleData[7]){
        //                 $singleData[7] = $cekData[3];
        //                 $resultShopee[] = $singleData;
        //                 break;
        //             }
        //         }
        //     }
        // }


        $pathShopee3 = request()->file('shopee3')->store('temp'); 
        $path2Shopee3 = storage_path('app').'/'.$pathShopee3;  
        $arrayShopee3 = Excel::toArray(new QasirDataGyrImport,$path2Shopee3);
        
        // foreach($arrayShopee3[0] as $singleData){
        //     if(str_contains($singleData[0], 'et_title_product_id') || str_contains($singleData[0], 'sales_info') || $singleData[0] == null || $singleData[0] == 'Kode Produk'){
        //         continue;
        //     }
        //     $singleData[3] = ltrim($singleData[3]);
        //     $singleData[] = $singleData[1] . $singleData[3];

        //     foreach ($resultArray as $cekData){
        //         if($cekData[4] == $singleData[26]){
        //             if($cekData[3] != $singleData[7]){
        //                 $singleData[7] = $cekData[3];
        //                 $singleData[0] = $singleData[7] . '';
        //                 $singleData[2] = $singleData[2] . '';
        //                 $resultShopee[] = $singleData;
        //                 break;
        //             }
        //         }
        //     }
        // }

        $allArrayShopee = array_merge($arrayShopee1, $arrayShopee2, $arrayShopee3);

        // dd(count($allArrayShopee));

        foreach($resultArray as $singleValidQasir){
            for ($x = 0; $x <= 2; $x++) {
                foreach($allArrayShopee[$x] as $singleDataAllShopee){
                    $singleDataAllShopee[] = $singleDataAllShopee[1] . ltrim($singleDataAllShopee[3]);
                    if($singleValidQasir[4] == $singleDataAllShopee[26] || str_contains($singleValidQasir[4], 'off')){
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
            for ($x = 0; $x <= 2; $x++) {
                foreach($allArrayShopee[$x] as $singleDataAllShopee){
                    if($singleValidQasir[0] == $singleDataAllShopee[1]){
                        $copyResultArray[] = $singleValidQasir;
                        $keyToDelete = array_search($singleValidQasir, $resultArray);
                        unset($resultArray[$keyToDelete]);
                        break;
                    }
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
            if(str_contains($singleGyr[4], 'off') || str_contains($singleGyr[4], 'Nama Variasi') || $singleGyr[8] < 1 ){
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
            if(str_contains($singleDps[4], 'off') || str_contains($singleDps[4], 'Nama Variasi') || $singleDps[8] < 1 ){
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

        $resultJson = json_encode($resultArray);
        
        // dd($resultJson);

        

        $pathShopee1 = request()->file('shopee1')->store('temp'); 
        $path2Shopee1 = storage_path('app').'/'.$pathShopee1;  
        $arrayShopee1 = Excel::toArray(new QasirDataGyrImport,$path2Shopee1);

        // dd($arrayShopee1);

        $resultShopee = array();

        // foreach($arrayShopee1[0] as $singleData){
        //     if(str_contains($singleData[0], 'et_title_product_id') || str_contains($singleData[0], 'sales_info') || $singleData[0] == null || $singleData[0] == 'Kode Produk'){
        //         continue;
        //     }
        //     $singleData[3] = ltrim($singleData[3]);
        //     $singleData[] = $singleData[1] . $singleData[3];

        //     foreach ($resultArray as $cekData){
        //         if($cekData[4] == $singleData[26]){
        //             if($cekData[3] != $singleData[7]){
        //                 $singleData[7] = $cekData[3];
        //                 $resultShopee[] = $singleData;
        //                 break;
        //             }
        //         }
        //     }
        // }

        $pathShopee2 = request()->file('shopee2')->store('temp'); 
        $path2Shopee2 = storage_path('app').'/'.$pathShopee2;  
        $arrayShopee2 = Excel::toArray(new QasirDataGyrImport,$path2Shopee2);

        // foreach($arrayShopee2[0] as $singleData){
        //     if(str_contains($singleData[0], 'et_title_product_id') || str_contains($singleData[0], 'sales_info') || $singleData[0] == null || $singleData[0] == 'Kode Produk'){
        //         continue;
        //     }
        //     $singleData[3] = ltrim($singleData[3]);
        //     $singleData[] = $singleData[1] . $singleData[3];

        //     foreach ($resultArray as $cekData){
        //         if($cekData[4] == $singleData[26]){
        //             if($cekData[3] != $singleData[7]){
        //                 $singleData[7] = $cekData[3];
        //                 $resultShopee[] = $singleData;
        //                 break;
        //             }
        //         }
        //     }
        // }


        $pathShopee3 = request()->file('shopee3')->store('temp'); 
        $path2Shopee3 = storage_path('app').'/'.$pathShopee3;  
        $arrayShopee3 = Excel::toArray(new QasirDataGyrImport,$path2Shopee3);
        
        // foreach($arrayShopee3[0] as $singleData){
        //     if(str_contains($singleData[0], 'et_title_product_id') || str_contains($singleData[0], 'sales_info') || $singleData[0] == null || $singleData[0] == 'Kode Produk'){
        //         continue;
        //     }
        //     $singleData[3] = ltrim($singleData[3]);
        //     $singleData[] = $singleData[1] . $singleData[3];

        //     foreach ($resultArray as $cekData){
        //         if($cekData[4] == $singleData[26]){
        //             if($cekData[3] != $singleData[7]){
        //                 $singleData[7] = $cekData[3];
        //                 $singleData[0] = $singleData[7] . '';
        //                 $singleData[2] = $singleData[2] . '';
        //                 $resultShopee[] = $singleData;
        //                 break;
        //             }
        //         }
        //     }
        // }

        $allArrayShopee = array_merge($arrayShopee1, $arrayShopee2, $arrayShopee3);

        // dd(count($allArrayShopee));

        foreach($resultArray as $singleValidQasir){
            for ($x = 0; $x <= 2; $x++) {
                foreach($allArrayShopee[$x] as $singleDataAllShopee){
                    $singleDataAllShopee[] = $singleDataAllShopee[1] . ltrim($singleDataAllShopee[3]);
                    if($singleValidQasir[4] == $singleDataAllShopee[26] || str_contains($singleValidQasir[4], 'off')){
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
            for ($x = 0; $x <= 2; $x++) {
                foreach($allArrayShopee[$x] as $singleDataAllShopee){
                    if($singleValidQasir[0] == $singleDataAllShopee[1]){
                        $copyResultArray[] = $singleValidQasir;
                        $keyToDelete = array_search($singleValidQasir, $resultArray);
                        unset($resultArray[$keyToDelete]);
                        break;
                    }
                }
            }
        }

        $exportNewFromQasir = new BerkasExport([
            $resultArray
        ]);
    
        return Excel::download($exportNewFromQasir, 'NewFromQasir.xlsx');
    }
}