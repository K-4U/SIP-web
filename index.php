<?php
include("SIPRequester.class.php");
//Script here to fetch all the info
$requester = new SIPRequester(gethostbyname('home.k-4u.nl'), 25566);

$requester->addValueToRequest('time');
$requester->addValueToRequest('weather');
$requester->addValueToRequest('uptime');
$requester->addValueToRequest('daynight');
$requester->addValueToRequest('players', 'latestdeath');
$requester->addValueToRequest('deaths');
$requester->addValueToRequest('tps');



function blockRequest($x, $y, $z, $dim, $side) {
  
  return
    [
      'x'         => $x,
      'y'         => $y,
      'z'         => $z,
      'dimension' => $dim,
      'side'      => $side,
    ];
}
$requester->addValueToRequest("energy", blockRequest(-44, 63, -23, 0, "up"));
$requester->addValueToRequest("energy", blockRequest(-46, 63, -23, 0, "up"));
$requester->addValueToRequest("energy", blockRequest(-48, 63, -23, 0, "up"));

$requester->addValueToRequest("fluid", blockRequest(-44, 64, -20, 0, "up"));
$requester->addValueToRequest("fluid", blockRequest(-46, 64, -20, 0, "up"));
//$requester->addValueToRequest("fluid", blockRequest(-48, 64, -20, 0, "up"));

$requester->doRequest();

$players = $requester->getValue('players');
$deaths = $requester->getValue('deaths');

$energyBlocks = $requester->getValue('energy');
$fluidBlocks = $requester->getValue('fluid');

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <meta charset="UTF-8">
    <title>Minecraft SIP</title>
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    
    <!-- Optional theme -->
    <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
    
    <!-- Latest compiled and minified JavaScript -->
    <script src="js/bootstrap.min.js"></script>
  
  </head>
  <body>
    <!--<div class="row">
      <nav class="navbar navbar-inverse">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed"
                    data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">My Minecraft server</a>
          </div>
          <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div>
        </div>
      </nav>
    </div>-->
    
    <div class="container">
      <div class="col-md-6 col-sm-12">
        <div class="panel panel-info">
          <div class="panel-heading">
            <div class="panel-title">
              Currently online:
            </div>
          </div>
          <table class="table">
            <thead>
              <tr>
                <th>Player</th>
                <th>Deaths</th>
                <th>Latest death</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($players as $player => $death) {
                echo "<tr><td>" . $player . "</td>";
                echo "<td>" . $deaths['LEADERBOARD'][$player] . "</td>";
                echo "<td>" . $death . "</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
      
      <div class="col-md-6 col-sm-12">
        <div class="panel panel-info">
          <div class="panel-heading">
            <div class="panel-title">
              Info
            </div>
          </div>
          <table class="table">
            <tbody>
              <tr>
                <th>Time</th>
                <td><?= $requester->getValue('time')[0] ?></td>
              </tr>
              <tr>
                <th>Day or night?</th>
                <td><?= $requester->getValue('daynight') == TRUE ? "Day" : "Night" ?></td>
              </tr>
              <tr>
                <th>Weather</th>
                <td><?= $requester->getValue('weather')[0] ?></td>
              </tr>
              <tr>
                <th>Uptime</th>
                <td><?= date("H:i:s", floor($requester->getValue('uptime') / 1000)) ?></td>
              </tr>
              <tr>
                <th>TPS (overworld)</th>
                <td><?= $requester->getValue('tps')['0']['tps'] ?></td>
              </tr>
              <tr>
                <th>TPS (nether)</th>
                <td><?= $requester->getValue('tps')['-1']['tps'] ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      
      <div class="col-md-12">
        <div class="panel panel-warning">
          <div class="panel-heading">
            <div class="panel-title">
              Blocks - Energy
            </div>
          </div>
          <table class="table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Energy</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($energyBlocks as $energyBlock){
                echo "<tr>";
                echo "<td style='width:10%'>" . $energyBlock['localized-name'] . "</td>";
                echo "<td style='width:10%'>" . $energyBlock['type'] . "</td>";
                echo "<td>";
                $perc = ($energyBlock['stored'] / $energyBlock['capacity']) * 100;
                ?>
                  <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="<?= $perc ?>" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: <?= $perc ?>%">
                    <?= $energyBlock['stored'] ?>/<?= $energyBlock['capacity'] ?>
                  </div>
                </div>
              <?php
                echo "</td>";
                echo "</tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
  
      <div class="col-md-12">
        <div class="panel panel-warning">
          <div class="panel-heading">
            <div class="panel-title">
              Blocks - Fluid
            </div>
          </div>
          <table class="table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Fluid</th>
                <th>Level</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($fluidBlocks as $fluidBlock){
                echo "<tr>";
                echo "<td style='width:10%'>" . $fluidBlock['localized-name'] . "</td>";
                echo "<td style='width:10%'>" . $fluidBlock['fluid'] . "</td>";
                echo "<td>";
                $perc = ($fluidBlock['stored'] / $fluidBlock['capacity']) * 100;
                ?>
                <div class="progress">
                  <div class="progress-bar" role="progressbar" aria-valuenow="<?= $perc ?>" aria-valuemin="0" aria-valuemax="100" style="min-width: 5em; width: <?= $perc ?>%">
                    <?= $fluidBlock['stored'] ?>/<?= $fluidBlock['capacity'] ?>
                  </div>
                </div>
                <?php
                echo "</td>";
                echo "</tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    
    </div>
  </body>
</html>