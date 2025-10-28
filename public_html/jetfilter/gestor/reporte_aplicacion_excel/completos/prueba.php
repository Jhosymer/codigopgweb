<?php
$G8=array('EUROPA' => array("Alemania","Francia","Italia","Reino Unido"),
'ASIA' => array("Rusia","Japón,"),
'AMERICA' => array ("Estados Unidos","Canada"),
'AFRICA' => array("Sudafrica"),
'OCEANIA' => array("Australia"));

$largo=0;
?>

<body>
    <table width="100%" border="1">
        <thead>
            <tr>
                <?php foreach($G8 as $key => $value): ?>
                    <th><?php echo $key; ?></th>
                <?php $largo = count($value) > $largo ? count($value) : $largo; //guardamos el largo máximo?>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php for($i=0; $i<$largo; $i++): ?>
                <tr>
                    <?php 
                    foreach($G8 as $key => $value) {
                        echo "<td>";
                        if(isset($value[$i])){
                            echo $value[$i];
                        }
                        echo "</td>";
                    }
                    ?>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
</body>