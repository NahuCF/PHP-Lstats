<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/index.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />

    <title>LStats - Sum</title>
</head>
<body>
    <div class="wrap">
        <div class="top">
            <a href="index">Home</a>
            <form class="form-summoner form-summoner--left-lupa" action="sum" method="GET">
                <input name="name" type="text" placeholder="Summoner">
                <i class="fas fa-search"></i>
            </form>
        </div>
        <div class="matchs-container">
            <div class="match">
                <div class="match__content">
                    <div class="match__left">
                        <div class="match__general-info">
                            <h3>Derrota</h3>
                            <div>
                                <p class="match__kda">1.59 KDA</p>
                                <P class="match__data--little">5 / 17 / 22</P>
                            </div>
                        </div>

                        <div class="match__per-minute">
                           <div class="match__per-minute__top">
                                <p>3 K/Min</p>
                                <p class="match__data--little">47%</p>
                           </div>
                           <div class="match__per-minute__bottom">
                                <p>3 CS/Min</p>
                                <p class="match__data--little">19 CS</p>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>