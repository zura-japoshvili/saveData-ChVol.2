<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaveData - ChVol.2</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php
        $pdo = new PDO('mysql:host=localhost;dbname=savedata_db;','root','');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $url = "https://api.kucoin.com/api/v1/market/stats?symbol=BTC-USDT";
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, $url);
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($resource);
        curl_close($resource);


        $data = json_decode($result, true);

        $time = $data['data']['time'];
        $buy = $data['data']['buy'];
        $sell = $data['data']['sell'];
        $averagePrice = $data['data']['averagePrice'];
        $high = $data['data']['high'];
        $low = $data['data']['low'];
        $changeRate = $data['data']['changeRate'];
        $changePrice = $data['data']['changePrice'];

        if(isset($_POST['submit'])){

            $statement =  $pdo->prepare("INSERT INTO data_info (time, buy, sell, av_price, high, low, ch_rate, ch_price)
            VALUE(:time, :buy, :sell, :av_price, :high, :low, :ch_rate, :ch_price)");
            
            $statement->bindValue(':time', $time);
            $statement->bindValue(':buy', $buy);
            $statement->bindValue(':sell', $sell);
            $statement->bindValue(':av_price', $averagePrice);
            $statement->bindValue(':high', $high);
            $statement->bindValue(':low', $low);
            $statement->bindValue(':ch_rate', $changeRate);
            $statement->bindValue(':ch_price', $changePrice);
        
            $statement->execute();
        }

    ?>
    <h1><?php echo $data['data']['symbol'] ?></h1>
    <p><?php echo $time ?></p>
    <div>
        <p class="buy">Buy:&ensp;<?php echo $buy ?></p>
        <p class="sell">Sell:&ensp;<?php echo $sell ?></p>
    </div>
    <h3>Average Price&ensp;<?php echo $averagePrice ?></h3>
    <div class="s-div">
        <p>High:&ensp;<?php echo $high ?></p>
        <p>Low:&ensp;<?php echo $low ?></p>
    </div>
    <div class="s-div">
        <p>ChangeRate:&ensp;<?php echo $changeRate ?></p>
        <p>ChangePrice:&ensp;<?php echo $changePrice ?></p>
    </div>
    <form method="post">
        <button class="btn" type="submit" name="submit">Save Data</button>
    </form>
</body>
</html>