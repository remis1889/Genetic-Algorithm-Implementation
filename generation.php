<?php
 
session_start();
error_reporting(1);
ini_set('max_execution_time', 300);
//generate permutations
function pc_permute($items, $perms = array( )) {
    if (empty($items)) {
        $return = array($perms);
    }  else {
        $return = array();
        for ($i = count($items) - 1; $i >= 0; --$i) {
             $newitems = $items;
             $newperms = $perms;
         list($foo) = array_splice($newitems, $i, 1);
             array_unshift($newperms, $foo);
             $return = array_merge($return, pc_permute($newitems, $newperms));
         }
    }
    return $return;
}

//calculate weight matrix
function calculate_weight($rsize)
{
    for($i=0,$j=$rsize;$i<$rsize;$i++,$j--)
    {
        $weight_vector[$i] = $j* pow(3, $j);
    } 
    return $weight_vector;
}

//calculate fitness
function fitness($weight,$ch,$rel,$rsize)
{
    foreach ($ch as $key => $value) { 
        $fitness[$key] = 0;
        for($i=0;$i<$rsize;$i++)
        {   
            $fitness[$key]+=exp($rel[$value[$i]])*$weight[$i]; 
        } 
    }
    return $fitness;
}

//create generation
function create_generation($chromosome, $fitness, $i)
{
    foreach($chromosome as $k => $v)
    {
        $data[$i]['id'] = rand(100,999); 
        $data[$i]['chromosome'] = $v;
        $data[$i]['fitness'] = $fitness[$k];
        $i++;
    }
    array_multisort($fitness, SORT_DESC, $data);
    return $data;
}
//end functions...

$generation = $_POST['gen'];

//initial population
if($generation==0)
{
    $rel_arr = $_POST['relevance'];
    $rsize = $_POST['rsize'];
    $max_size = $_POST['max_size'];
    
    for($i=0;$i<$rsize;$i++)
    {
        $results[$i] = $i+1;
        $relevance[$i+1] = $rel_arr[$i];
    } 
    
    $chromosome = pc_permute($results);
    $weight_vector = calculate_weight($rsize);
    $fitness = fitness($weight_vector,$chromosome,$relevance,$rsize);
    $data = create_generation($chromosome, $fitness, 0);
        
    array_splice($data, $max_size);
              
    //set session variables
    $_SESSION['relevance'] = $rel_arr;
    $_SESSION['rsize'] = $rsize;
    $_SESSION['data'] = $data;
    $_SESSION['fitness_sum'] = $fitness_sum;
    $_SESSION['psize'] = $max_size;
}
else 
{
    $rel_arr = $_SESSION['relevance'];
    $rsize = $_SESSION['rsize'];
    $data = $_SESSION['data'];
    $psize = $_SESSION['psize'];
    
    //    selection();

    $q = intval($psize/3);
    $new_gen = array_slice($data,0,$q);
    array_splice($data, 0, $q);
    $cq = intval($rsize/3);
    $cr = $rsize%3;

    //    crossover();
    $count = 0;
    $j = 0;
    $index_arr = array();
    $new_chromosome = array();
    
    while(count($new_chromosome)!=count($data))
    {
        $index = array_rand($data,2);
        if(!(in_array($index, $index_arr)) && ($index[0]!=$index[1]) && !(in_array(array_reverse($index), $index_arr)))
        {
            $index_arr[$j] = $index;
            $j++;
            
            $parentA = $data[$index[0]]['chromosome'];
            $parentB = $data[$index[1]]['chromosome'];
            
            $offspring1 = array_slice($parentA, $cq, $cq+$cr, true);
            $remainsA1 = array_slice($parentA, 0, $cq, true);
            $remainsA2 = array_slice($parentA, $cq+$cr+1, $cq, true);
            $remainsA = $remainsA1 + $remainsA2;
            
            $offspring2 = array_slice($parentB, $cq, $cq+$cr,true);
            $remainsB1 = array_slice($parentB, 0, $cq, true);
            $remainsB2 = array_slice($parentB, $cq+$cr+1, $cq, true);
            $remainsB = $remainsB1 + $remainsB2;
            
            for($k=0,$m=$cq+$cr+1,$n=$cq+$cr+1;;$k++)
            { 
                if(!(in_array($parentB[$n], $offspring1)))
                {
                    $offspring1[$m] = $parentB[$n];
                    $m++;
                    if($m==$rsize)
                        $m=0;
                }    
                $n++;
                if($n==$rsize)
                    $n=0;
                if(count($offspring1)==$rsize)
                    break;
            }
            
            for($k=0,$m=$cq+$cr+1,$n=$cq+$cr+1;;$k++)
            { 
                if(!(in_array($parentA[$n], $offspring2)))
                {
                    $offspring2[$m] = $parentA[$n];
                    $m++;
                    if($m==$rsize)
                        $m=0;
                }    
                $n++;
                if($n==$rsize)
                    $n=0;
                if(count($offspring2)==$rsize)
                    break;
            }
    
            ksort($offspring1);
            while(in_array($offspring1, $new_chromosome))
            {
                shuffle($offspring1);
            }        
            $new_chromosome[$count++] = $offspring1;
            
            ksort($offspring2);
            while(in_array($offspring2, $new_chromosome))
            {
                shuffle($offspring2);
            }
            $new_chromosome[$count++] = $offspring2;
        }
    }
 
    //    fitness();
    
    $weight_vector = calculate_weight($rsize);
    $fitness = fitness($weight_vector,$new_chromosome,$rel_arr,$rsize);
    $new_data = create_generation($new_chromosome, $fitness, count($new_gen));
   
    $data = array_merge($new_gen,$new_data);
    
    $f = array();
    foreach ($data as $key => $row)
    {
        $f[$key] = $row['fitness'];
    }
    array_multisort($f, SORT_DESC, $data);
        
    //set session variables
    $_SESSION['data'] = $data;    
}
?>   

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>GA Implementation</title>
    </head>
    <body style="font-size: 14px; font-weight: normal; background: lavender">
        <div style="height: 100px;"></div>
        <table border="1" cellpadding="0" cellspacing="0" width="25%" align="center">
            <thead>
                <tr>
                    <th colspan="4" align="center" height="90" style="font-size: 28px; font-weight: bold;">
                        Generation <?php echo $generation; ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td align="center">No</td>
                    <td align="center">ID</td>
                    <td align="center">Chromosomes</td>
                    <td align="center">Fitness</td>
                </tr>
                 <?php
                    $i = 0;        
                    foreach ($data as $key => $value) {
                 ?>
                <tr>
                    <td align="center"><?php echo ++$i; ?></td>
                    <td align="center"><?php echo $value['id']; ?></td>
                    <td>
                        <table border="5" style="border-color: blue" cellpadding="0" cellspacing="0" width="75%" align="center">
                                <tr>
                                    <?php
                                    foreach ($value["chromosome"] as $val) {
                                    ?>
                                    <td align="center"><?php echo $val; ?></td>
                                    <?php
                                    }
                                    ?>
                                </tr>
                        </table>

                    </td>
                    <td align="center"><?php echo $value['fitness']; ?></td>
                </tr>
                <?php
                 }
                ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4" height="50" align="center">
                        <form name="fitness_form" method="post" action="generation.php">
                            <input type="submit" value="Proceed to Next Generation" name="proceed" id="proceed" />
                            <input name="gen" type="hidden" value="<?php echo ++$generation; ?>"/>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
