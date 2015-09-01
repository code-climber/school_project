<h1>Bienvenue sur le site de l'école !</h1>

<table>
    <thead>
        <th>Elève</th>
        <th>Age</th>
        <th>Classe</th>
        <th>Administration</th>
    </thead>
    
    <tbody>
        <?php foreach($aEleves as $oEleve): ?>
    <tr>
        <td>
            <a href="index.php?page=Eleve&idEleve=<?php echo $oEleve->getId(); ?>"><?php echo $oEleve->getNom()." ".$oEleve->getPrenom(); ?></a>
        </td>
        <td><?php echo $oEleve->getAge(); ?></td>
        
            <?php $classe = $oEleve->getClasse();?>
        <td> <?php echo $classe[0]; ?> </td>
        <td><a href="index.php?page=Eleve&idEleve=<?php echo $oEleve->getId(); ?>">modifier</a></td>
        
        <td><a href="index.php?page=deleteOneEleve&idEleve=<?php echo $oEleve->getId(); ?>">Supprimer</a></td>
    </tr>
        
        <?php endforeach; ?>
    
    </tbody>

</table>

<ul>
    <li><a href="index.php?page=Eleve">Ajouter un élève</a></li>
    <li><a>Supprimer tous les élèves</a></li>
</ul>



