<h2>Informations générales</h2>
<p>
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $error; ?>
    </div>
<?php endif; ?>
</p>

<form method="post" action="index.php?page=Eleve">

    <input type="hidden" name="idEleve" value="<?php echo $idEleve; ?>">
    <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" required <?php if (!empty($nom)) { ?> value="<?php echo $nom; ?>"<?php } ?>>
    </div>

    <div class="form-group">
        <label for="prenom">Prénom</label>
        <input type="prenom" name="prenom" id="prenom" required <?php if (!empty($prenom)) { ?> value="<?php echo $prenom; ?>"<?php } ?> >
    </div>

    <div class="form-group">
        <label for="age">Age</label>
        <input type="number" name="age" id="age" required <?php if (!empty($age)) { ?> value="<?php echo $age; ?>"<?php } ?> >
    </div>

    <div class="radio">
        <label><input type="radio" name="sexe" value="m" <?php if ($sexe == 'm') { ?>checked<?php } ?>>Garçon</label>
    </div>
    <div class="radio">
        <label><input type="radio" name="sexe" value="f" <?php if ($sexe == 'f') { ?>checked<?php } ?>>Fille</label>
    </div>

    <div class="form-group">
        <?php if(array_key_exists('idEleve', $_GET)) { ?>
            <label for="currentClasse">Classe courante</label>
            <input type="text" name="currentClasse" id="currentClasse" value="<?php echo $classe[0]; ?>">
            <div class="form-group">
            <label for="classe">Mettre à jour la classe:</label>
            <select class="form-control" id="classe" name="classe">
                <?php foreach ($aClasses as $oClasse): ?>
                    <option value="<?php echo $oClasse->getId(); ?>">
                        <?php echo $oClasse->getNomClasse(); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            </div>
        <?php } else { ?>
            <label for="classe">Classe:</label>
            <select class="form-control" id="classe" name="classe">
                <?php foreach ($aClasses as $oClasse): ?>
                    <option value="<?php echo $oClasse->getId(); ?>">
                        <?php echo $oClasse->getNomClasse(); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php } ?>
    </div>

    <button type="submit" class="btn btn-default">Soumettre</button>
</form>

<?php if(array_key_exists('idEleve', $_GET)): ?>
<h2>Notes de l'élève</h2>
<table class="table">
    <thead>
        <tr>
            <th>Matières</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($aNotes as $oNote): ?>
        <?php // var_dump($aNotes); ?>
            <tr>
                    <?php // foreach($oNote->getMatiere()): ?>
                    <td><?php echo $oNote->getMatiere(); ?></td>
                    <?php 
                        $aNotes = $oNote->getNote();
                    ?>
                    <td>
                        <?php
                        //pour utiliser implode sur un tableau associatif quand on n'a pas PHP 5.5 :
                        $result = array();
                        foreach($aNotes as $row){
                            $result[]=$row['note'];
                        }
                        echo implode(", ", $result);
                            
                        ?>
                    </td>
                   <?php // endforeach; ?>
            </tr>
             
        <?php endforeach; ?>
    </tbody>
</table>


<h2>Ajouter des notes</h2>
<p>
    <?php if (!empty($errorNote)): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $errorNote; ?>
    </div>
<?php endif; ?>
</p>
<form method="post" action="index.php?page=AddNote&idEleve=<?php echo $idEleve; ?>">
    <input type="hidden" name="idEleve" value="<?php echo $idEleve; ?>">
    <div class="form-group">
        <label for="matiere">Choisir une matière :</label>
        <select class="form-control" id="matiere" name="matiere">
            <?php foreach ($aMatieres as $oMatiere): ?>
                <option value="<?php echo $oMatiere->getId(); ?>">
                    <?php echo $oMatiere->getLibelle(); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="note">Choisir une note :</label>
        <select class="form-control" id="note" name="note">
            <?php for ($i = 0; $i <= 20; $i++): ?>
                <option value="<?php echo $i ?>">
                    <?php echo $i ?>
                </option>
            <?php endfor; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-default">Ajouter la note</button>
</form>
<?php endif; ?>
