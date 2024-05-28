<!DOCTYPE html>
<html>
<head>
    <title>Packing List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            width: 100%;
            text-align: center;
        }
        .info {
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
        .info p {
            width: 48%;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px; /* Adjust font size for table to prevent cutting off */
        }
        table, th, td {
            border: 1px solid black;
            
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        .layout {
            width: 100%;
            display: flex;
            gap: 200px;
            justify-content: space-between;
        }

        .grow1 { 
            flex-grow: 1; 
        }
        .info-table {
    width: 100%;
    border-collapse: collapse;
}

.info-table th, .info-table td {
    border: 1px solid #A9A9A9;
    padding: 8px;
}

.info-table th {
    background-color: #f2f2f2;
    text-align: left;
}

.info-table tr:nth-child(even) {
    background-color: #f9f9f9;
}
.summary-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px; /* Tambahkan jarak antara tabel jika diperlukan */
}

.summary-table th, .summary-table td {
    border: 1px solid #A9A9A9;
    padding: 8px;
}

.summary-table th {
    background-color: #f2f2f2;
    text-align: left;
}

.summary-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.header-table {
    width: 100%;
    border-collapse: collapse;
}

.header-table th, .header-table td {
    border: 1px solid #A9A9A9;
    padding: 10px;
    text-align: center;
    vertical-align: top;
}

.header-table th {
    background-color: #f2f2f2;
}

.header-table th:nth-child(2) {
    width: 70%;
    text-align: center;
}

.header-table .empty-row {
    height: 100px; /* Atur tinggi sel sesuai kebutuhan */
    vertical-align: bottom;
    padding: 0; /* Hilangkan padding untuk menghilangkan jarak tambahan */
   
    align-items: flex-end; /* Pastikan konten berada di bawah */
    justify-content: flex-start; /* Sesuaikan jika ingin teks di kiri */
}

.header-table .empty-row p {
    margin: ; /* Hilangkan margin default dari <p> */
    width: 100%;
    text-align: center; /* Sesuaikan posisi teks jika diperlukan */
}




    </style>
</head>
<body>
    <div class="header">
        <h2>Packing List</h2>
    </div>
   <div>
    <table class="info-table">
        <thead>
            <tr>
                <th>DO</th>
                <td>{{ $order->delivery_order }}</td>
            </tr>
            <tr>
                <th>Customer</th>
                <td>{{ $customer->customer_name }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{{ $customer->address }}</td>
            </tr>
            <tr>
                <th>Contact</th>
                <td>{{ $customer->contact_person }} - {{ $customer->contact_number }}</td>
            </tr>
        </thead>
    </table>
    
   </div>
    <br>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Carton Total</th>
                <th>Qty / Carton</th>
                <th>Qty Total</th>
                <th>Product Name</th>
                <th>Weight / unit</th>
                <th>Weight Total</th>
                <th>Volume / unit</th>
                <th>Volume Total</th>
                <th>Dimension</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cartons as $carton)
                @php
                    $totalCartonsPerItem = ceil($carton->quantity / $carton->items_per_carton);
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $totalCartonsPerItem }}</td>
                    <td>{{ $carton->items_per_carton }}</td>
                    <td>{{ $carton->quantity }}</td>
                    <td>{{ $carton->product->product_name }}</td>
                    <td>{{ $carton->product->weight_per_unit }}</td>
                    <td>{{ $carton->quantity * $carton->product->weight_per_unit }}</td>
                    <td>{{ $carton->product->volume_per_unit }}</td>
                    <td>{{ $carton->quantity * $carton->product->volume_per_unit }}</td>
                    <td>{{ $carton->product->dimension }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Total Cartons</th>
                    <td>{{ $totalCartons }}</td>
                </tr>
                <tr>
                    <th>Total Quantity</th>
                    <td>{{ $totalQty }}</td>
                </tr>
                <tr>
                    <th>Total Weight</th>
                    <td>{{ $totalWeight }} kg</td>
                </tr>
                <tr>
                    <th>Total Volume</th>
                    <td>{{ $totalVolume }} m³</td>
                </tr>
            </thead>
        </table>
        
    </div>
<br>
    <div>
        <table class="header-table">
            <thead>
                <tr>
                    <th>Expedition</th> 
                    <th>Jakarta, {{ date('d F Y') }}<br>Warehouse</th>
    
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="empty-row"><p>_________________</p></td>
                    <td class="empty-row"><p>_________________</p></td>
                </tr>
            </tbody>
        </table>
    </div>
    
    

      
</body>
</html>
