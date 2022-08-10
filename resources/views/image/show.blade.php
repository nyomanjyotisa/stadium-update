<!DOCTYPE html>
<html>
<head>
	<title>Produk Shopee</title>
</head>
<body>
 
	<style type="text/css">
		.pagination li{
			float: left;
			list-style-type: none;
			margin:5px;
		}
	</style>
 
	<h3>Data Produk</h3>

    <p>Cari Data Produk :</p>
	<form action="/images/cari" method="GET">
		<input type="text" name="cari" placeholder="Cari Produk .." value="{{ old('cari') }}">
		<input type="submit" value="CARI">
	</form>
		
	<br/>
 
 
	<table border="1">
		<tr>
			<th>Nama</th>
			<th>Image Shopee</th>
            <th>Image</th>
            <th>Action</th>
		</tr>
		@foreach($products as $product)
		<tr>
			<td rowspan="3">{{ $product->name }}</td>
			<td rowspan="3"><img src="{{ $product->shopee_image }}" alt="image" width="200px"></td>
            <td>
                <img src="{{ $product->image1 }}" alt="image1" width="50px">
                <img src="{{ $product->image2 }}" alt="image2" width="50px">
                <img src="{{ $product->image3 }}" alt="image3" width="50px">
            </td>
            <td rowspan="3">
                <button>Upload Image</button>
            </td>
		</tr>
        <tr>
            <td>
                <img src="{{ $product->image4 }}" alt="image4" width="50px">
                <img src="{{ $product->image5 }}" alt="image5" width="50px">
                <img src="{{ $product->image6 }}" alt="image6" width="50px">
            </td></tr>
        <tr>
            <td>
                <img src="{{ $product->image7 }}" alt="image7" width="50px">
                <img src="{{ $product->image8 }}" alt="image8" width="50px">
                <img src="{{ $product->image9 }}" alt="image9" width="50px">
            </td></tr>
		@endforeach
	</table>
 
	<br/>
	Halaman : {{ $products->currentPage() }} <br/>
	Jumlah Data : {{ $products->total() }} <br/>
	Data Per Halaman : {{ $products->perPage() }} <br/>
 
 
	{{ $products->links() }}
 
 
</body>
</html>