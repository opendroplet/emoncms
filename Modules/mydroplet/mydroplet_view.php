<!-- bring in the emoncms path variable which tells this script what the base URL of emoncms is -->
<?php global $path; 

$apikey = get('apikey');

?>

<!-- feed.js is the feed api helper library, it gives us nice functions to use within our program that
calls the feed API on the server via AJAX. -->
<script language="javascript" type="text/javascript" src="<?php echo $path; ?>Modules/feed/feed.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $path; ?>Modules/mydroplet/graph.js"></script>

<!-- defenition of the style/look of the elements on our page (CSS stylesheet) -->
<style>

  .droplet-main-title {
    font-weight:bold; 
    font-size:32px; 
    color:#aaa; 
    padding-top:20px
  }
  
  .droplet-row {
    width:100%;
    padding-left:30px;
  }

  .droplet-item {
    width:50%;
    float:left;
    padding-top:20px
    margin-left: 45px;
  }

  .flow-value {
    font-weight:bold; 
    font-size:100px; 
    color:#0699fa; 
    padding-top:45px;
  }

  .flow-title {
    font-weight:bold; 
    font-size:22px; 
    color:#aaa; 
    padding-top:20px
  }
  
  .flow-day {
    font-weight:bold; 
    font-size:50px; 
    color:#fff; 
    padding-top:25px;
  }
    
  .flow-week {
    font-weight:bold; 
    font-size:50px; 
    color:#fff; 
    padding-top:25px;
  }

  .flow-goal {
    font-weight:bold; 
    font-size:50px; 
    color:#fff; 
    padding-top:25px;
  }
  
  .flow-savings {
    font-weight:bold; 
    font-size:50px; 
    color:#fff; 
    padding-top:25px;
  }

  @media screen and (max-width:321px) {
    /* smaller screens */

    body {
      padding:0 0 0 0;
    }

    .navbar-fixed-top {
      margin-right:0px;
      margin-left:0px;
    }

    .navbar-fixed-top .navbar-inner {
      padding:0 0 0 0;
    } 

    .navbar .btn-navbar {
      padding:0 0 0 0;
    }

    .btn {
      padding:0 20px 0 0;
    }

    #config, #footer {
      display:none;
    }

    #bound {
      float:left;
      width:60%;
      height:90%;
      margin-top:20px;
    }

    .droplet-main-title {
      position: absolute;
      top:5px;
      left:100px;
      font-size:12px; 
      padding-top:5px;
    }
    
    .droplet-row {
      float:right;
      width:34%;
      padding-left: 0px;
    }

    .droplet-inner-row {
      float:right;
      width:100%;
      padding-left: 0px;
    }

    .droplet-item {
      width:100%;
      float:left;
      padding-top:5px
      margin-left: 5px;
    }

    .flow-value {
      position:absolute;
      top: 5px;
      left: 190px;
      font-weight:bold; 
      font-size:14px; 
      color:#0699fa; 
      padding-top:5px;
    }

    .flow-title {
      font-weight:bold; 
      font-size:10px; 
      color:#aaa; 
      padding-top:2px;
      padding-bottom:0px;
    }

    .flow-value-title {
      display:none;
    }

    .flow-day {
      font-weight:bold; 
      font-size:10px; 
      color:#fff; 
      padding-top:3px;
    }
      
    .flow-week {
      font-weight:bold; 
      font-size:10px; 
      color:#fff; 
      padding-top:3px;
    }

    .flow-goal {
      font-weight:bold; 
      font-size:10px; 
      color:#fff; 
      padding-top:3px;
    }
    
    .flow-savings {
      font-weight:bold; 
      font-size:10px; 
      color:#fff; 
      padding-top:3px;
    }
  }

</style>

<!-- The three elements: title, power value and kwhd value that makes up our page -->
<!-- margin: 0px auto; max-width:320px; aligns the elements to the middle of the page -->

<div id="config" style="margin: 0px auto; max-width:320px; display:none">
    
    <div class="droplet-main-title">My Droplet config</div>
    <br><br>
    
    <p><b style="color:#0699fa">Flow feed (Liter):</b></p>
    <select id="flowfeed" style="width:290px"></select>
    <br><br>
    
    <p><b style="color:#0699fa">Bar graph feed:</b></p>
    <select id="dailyfeed" style="width:290px"></select>
    
    <p><b style="color:#0699fa">Bar graph feed type:</b></p>
    <select id="dailytype" style="width:290px">
        <option value=0>Flow per minute</option>        
        <option value=1>Flow per hour</option>        
        <option value=1>Flow per day</option>        
    </select>
    <br><br>
    
    <button id="configsave" class="btn btn-primary">Save</button>
    
</div>



<div id="powerblock">
<div id="config" style="height:20px; border-bottom:1px solid #333; padding:8px;">
    <div style="float:right;">
        <!--<span style="color:#fff; margin-right:10px" >Settings</span>-->
        <i id="openconfig" class="icon-wrench icon-white" style="cursor:pointer"></i>
    </div>
</div>

    <div style="width:100%;">
        <div class="droplet-row">
            <div class="droplet-item">
            <div class="droplet-main-title">My Water Use</div>
                <div class="flow-value-title">Current Use</div>
                <div class="flow-value"><span id="flow"></span>L/s</div>
            </div>
            <div class="droplet-item">
                <div class="droplet-inner-row">
                    <div class="droplet-item">
                        <div class="flow-title">Total today</div>
                        <div class="flow-day"><span id="flow-day"></span>L</div>
                    </div>
                    <div class="droplet-item">
                        <div class="flow-title">Total this week</div>
                        <div class="flow-week"><span id="flow-week"></span>L</div>
                    </div>
                </div>
                <div class="droplet-inner-row">
                    <div class="droplet-item">
                        <div class="flow-title">Your goal today</div>
                        <div class="flow-goal-icon"></div>
                        <div class="flow-goal"><span id="flow-goal"></span>L</div>
                    </div>
                    <div class="droplet-item">
                        <div class="flow-title">Your week's savings</div>
                        <div class="flow-week-savings">£<span id="savings"></span></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="bound">
            <canvas id="myCanvas" ></canvas>
        </div>
    </div>
</div>

<script>

    var windowheight = $(window).height();
    
    // The feed api library requires the emoncms path
    var path = "<?php echo $path; ?>";
    var apikey = "<?php echo $apikey; ?>"; 

    feed.apikey = apikey;
    
    var daily = [];

    // Set the background color to dark grey - looks nice on a mobile.
    $("body").css('background-color','#222');
    
    $(window).ready(function(){
        $("#footer").css('background-color','#181818');
        $("#footer").css('color','#999');
    });
    
    var refresh = true;
    // Page and canvas widths
    var bound = {};
    update_graph_size();

    $(window).resize(function(){

        update_graph_size();
        graph.draw("myCanvas",[daily]);
        
        refresh = true;
    });

    var daily_data = [];

    // used for updating every 5 
    var updateinst = false;

    // Load Feeds used from mydroplet table  
    var config = {};
    $.ajax({ url: path+"mydroplet/get.json?apikey="+apikey, dataType: 'json', async: false, success: function(data) {config = data;} });

    if (!config) config = {flowfeed:0, dailyfeed:0, dailytype:0};
    if (config.flowfeed==undefined) config.flowfeed = 0;
    var flowfeed = parseInt(config.flowfeed); 
    if (config.dailyfeed==undefined) config.dailyfeed = 0;
    var dailyfeed = parseInt(config.dailyfeed);
    if (config.dailytype==undefined) config.dailytype = 0;
    var dailytype = parseInt(config.dailytype);

    // If no config then show config interface
    if (flowfeed==0 || dailyfeed==0) 
    {
        // Populate config feed list selectors
        var feeds = feed.list();
        var out = ""; for (z in feeds) out +="<option value="+feeds[z].id+">"+feeds[z].name+"</option>";
        $("#flowfeed").html(out);
        $("#dailyfeed").html(out);

        $("#config").show();
        $("#powerblock").hide();
    } else {
        update();
        updateinst = setInterval(update,5000);
    }
    
    function update_graph_size()
    {
        bound.width = $("#bound").width();
        bound.height = $("#bound").height();

        $("#myCanvas").attr('width',bound.width);
        $("#myCanvas").attr('height',bound.height);
        
        graph.width = bound.width;
        graph.height = bound.height;
    }

    function update()
    {
        if (refresh) {
        
            var ndays = Math.floor(graph.width / 40);
            var timeWindow = (3600000*24*ndays);    //Initial time window
            var start = +new Date - timeWindow; //Get start time
            var end = +new Date;                    //Get end time

            var d = new Date()
            var n = d.getTimezoneOffset();
            var offset = n / -60;

            var interval = 3600*24;
            var datastart = (Math.round((start/1000.0)/interval) * interval); //+3600*offset;

            daily_data = feed.get_average(dailyfeed,datastart*1000,end+(interval*1000),interval);
            if (daily_data.success != undefined) daily_data = [];
        }
        refresh = false; 

        // Get latest feed values from the server (this returns the equivalent of what you see on the feed/list page)
        feeds = feed.list_by_id();

        // Make a copy of the last 7 days of kwh totals data so that we can calculate today's amount with out always adding a new entry
        var daily_data_copy = eval(JSON.stringify(daily_data));

        daily = [];
        if (daily_data_copy.length>0)
        {
            if (dailytype==0)
            {
                console.log(daily_data_copy)
                var lastday = daily_data_copy[daily_data_copy.length-1][0];
                daily_data_copy.push([lastday+24*3600*1000,feeds[dailyfeed]]);

                for (var z=1; z<daily_data_copy.length; z++)
                {
                    var kwh = (daily_data_copy[z][1] - daily_data_copy[z-1][1]) * 0.001;
                    daily.push([daily_data_copy[z][0],kwh]);
                }
                
                $("#flow-day").html((daily[daily.length-1][1]*1).toFixed(1));
            }
            else if (dailytype==1)
            {
                var lastday = daily_data_copy[daily_data_copy.length-1][0];
                daily_data_copy.push([lastday+24*3600*1000,feeds[dailyfeed]]);
                
                for (var z=1; z<daily_data_copy.length; z++)
                {
                    var kwh = (daily_data_copy[z][1] - daily_data_copy[z-1][1]);
                    daily.push([daily_data_copy[z][0],kwh]);
                }
                
                $("#flow-day").html((daily[daily.length-1][1]*1).toFixed(1));
            }
            else if (dailytype==2)
            {
                var lastday = daily_data_copy[daily_data_copy.length-1][0];
                daily_data_copy.push([lastday+24*3600*1000,feeds[dailyfeed]]);
                daily = daily_data_copy;
                
                $("#flow-day").html((daily[daily.length-1][1]*1).toFixed(1));
            }
            else if (dailytype==3)
            {
                for (var z=1; z<daily_data_copy.length; z++)
                {
                    var kwh = daily_data_copy[z][1]*0.024;
                    daily.push([daily_data_copy[z][0],kwh]);
                }
                $("#flow-day").html("---");
            }
        }
        newheight = windowheight-320;
        if (newheight>350) newheight = 350;
        
        $("#bound").height(newheight);
        bound.height = newheight;
        $("#myCanvas").attr('height',bound.height);
        graph.height = bound.height;
        
        graph.draw("myCanvas",[daily]);
        
        $("#flow").html(feeds[flowfeed]);
    }

    $("#openconfig").click(function(){
    
        // Load feed list, populate feed selectors and select the selected feed
        var feeds = feed.list();
        var out = ""; for (z in feeds) out +="<option value="+feeds[z].id+">"+feeds[z].name+"</option>";
        $("#flowfeed").html(out);
        $("#flowfeed").val(flowfeed);
        $("#dailyfeed").html(out);
        $("#dailyfeed").val(dailyfeed);
        $("#dailytype").val(dailytype);
        
        // Switch to the config interface
        $("#config").show();
        $("#powerblock").hide();
        
        if (updateinst) clearInterval(updateinst);
    });

    $("#configsave").click(function(){
    
        flowfeed = $("#flowfeed").val();
        dailyfeed = $("#dailyfeed").val();
        dailytype = $("#dailytype").val();

        // Restart interface update
        updateinst = setInterval(update,5000);

        refresh = true; 
        update();
        
        // Switch to main view 
        $("#config").hide();
        $("#powerblock").show();

        // Save config to db
        var config = {flowfeed: flowfeed, dailyfeed: dailyfeed, dailytype: dailytype};
        $.ajax({ url: path+"mydroplet/set.json", data: "data="+JSON.stringify(config), async: false, success: function(data){} });
    });
    
    
</script>
