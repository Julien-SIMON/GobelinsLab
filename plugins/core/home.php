<?php

	
    //echo get_auth('FACEBOOK','gofthepunk@hotmail.com','10152484350651682');
    print_r($_SESSION); echo '<BR><BR>';
	$user = new user($_SESSION['USER_ID']);
	print_r($user->groupIdArray).'<BR>';
	echo '<BR>';
	echo _('a').'<BR>';
	echo gettext('#1').'<BR>';
    //echo get_ini('local_register')."<BR>";
    
    //echo '<br><br>'.secure($_SESSION['USER_ID'],get_object_id('core_plugins','1')).'<br><br><br>';
    
    /*
    $secureM = new secureManager();
    $user = new user($_SESSION['USER_ID']);
	
	if($secureM->getId($user->objectId,get_object_id('core_plugins','1'))==0){
    	$secureM->create($user->objectId,get_object_id('core_plugins','1'),10);
    	echo 'SecureID: '.$secureM->getId($user->objectId,get_object_id('core_plugins','1')).' / user object id: '.$user->objectId.' / Plugins object id: '.get_object_id('core_plugins','1').'<BR>';
	}
    if(lockIt(get_object_id('core_plugins','1'))) {
    	echo 'access granted! '.lockIt(get_object_id('core_plugins','1')).'<BR>';
    } else {
    	echo 'access denied! '.lockIt(get_object_id('core_plugins','1')).'<BR>';
    }
    */
	echo '
	<span class="iconfa-adn"></span>
<table data-role="table" id="table-column-toggle" data-mode="columntoggle" class="ui-responsive table-stroke">
     <thead>
       <tr>
         <th data-priority="2">Rank</th>
         <th>Movie Title</th>
         <th data-priority="3">Year</th>
         <th data-priority="1"><abbr title="Rotten Tomato Rating">Rating</abbr></th>
         <th data-priority="5">Reviews</th>
       </tr>
     </thead>
     <tbody>
       <tr>
         <th>1</th>
         <td><a href="http://en.wikipedia.org/wiki/Citizen_Kane" data-rel="external">Citizen Kane</a></td>
         <td>1941</td>
         <td>100%</td>
         <td>74</td>
       </tr>
       <tr>
         <th>2</th>
         <td><a href="http://en.wikipedia.org/wiki/Casablanca_(film)" data-rel="external">Casablanca</a></td>
         <td>1942</td>
         <td>97%</td>
         <td>64</td>
       </tr>
       <tr>
         <th>9</th>
         <td><a href="http://en.wikipedia.org/wiki/Singin%27_in_the_Rain" data-rel="external">Singin\' in the Rain</a></td>
         <td>1952</td>
         <td>89%</td>
         <td>85</td>
       </tr>
       <tr>
         <th>10</th>
         <td class="title"><a href="http://en.wikipedia.org/wiki/Inception" data-rel="external">Inception</a></td>
         <td>2010</td>
         <td>84%</td>
         <td>78</td>
       </tr>
     </tbody>
   </table>
	';
?>