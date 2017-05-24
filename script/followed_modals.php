<div id="addModal" class="modal fade" role="dialog">
    <!-- Modal pour l'ajout d'une mesure -->
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Ajouter une nouvelle mesure</h2>
                <p>(Id individu <?php echo $idfollowed; ?>: , Id Staff: <?php echo $idstaff; ?>)</p>
            </div>
            <div class="modal-body">
                <form>
                    <input type="text" class="form-control" name="type"
                    placeholder="Type de mesure (Ex : taille)" list="meas_sugg"
                    required>
                    <datalist id="meas_sugg">
                    <?php create_autocplt_list($meas_types) ?>
                    </datalist>
                    <input type="number" step="0.01" class="form-control"
                    name="value" placeholder="Valeur (Ex : 1,64)" required>
                    <input type="text" class="form-control" name="unit"
                    placeholder="Unité (Ex : cm)" list="unit_sugg" required>
                    <datalist id="unit_sugg">
                    <?php create_autocplt_list($meas_units) ?>
                    </datalist>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                onclick="addMeasure(<?php echo $idfollowed.', '.$idstaff; ?>)"
                data-dismiss="modal">Valider</button>
            </div>
        </div>
    </div>
</div>

<div id="addRelationModal" class="modal fade" role="dialog">
    <!-- Modal d'ajoput d'une relation entre 2 followed -->
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Add a new relationship</h2>
                <p>(Id individu <?php echo $idfollowed; ?>: , Id Staff: <?php echo $idstaff; ?>)</p>
            </div>
            <div class="modal-body">
                <form>
                    <div class='input-group'>
                    <label>Relation type</label>
                    <input type="text" class="form-control" name="type_rel"
                    placeholder="Pack, flamboyance, pride, ..." required>
                    <label>With followed identified by</label>
                    <input type="number" class="form-control" name="other_followed"
                    placeholder="1, 12, ..." required>
                    <label>Relation began on the
                    <input type="date" class="form-control" name="begin"
                    placeholder="yyyy-mm-dd">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                onclick="addRelationship(<?php echo $idfollowed.', '.$idstaff; ?>)"
                data-dismiss="modal">Valider</button>
            </div>
        </div>
    </div>
</div>

<div id="infoModal" class="modal fade" role="dialog">
    <!-- Modal pour d'ajouts d'informations sur le followed -->
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Modifier les informations du <?php echo ucfirst($search_res['binomial_name']); ?> </h2>
            </div>
            <div class="modal-body">
                <h5>Votre commentaire:</h5>
                <h6>(Format date : YYYY-MM-DD)</h6>
                <form>
                    Date de naissance:
                    <input type="text" class="form-control" name="birth" placeholder="Date de naissance"
                    value="<?php echo ($search_res['birth'] ? $search_res['birth'] : ""); ?>">
                    Date de décès:
                    <input type="text" class="form-control" name="death" placeholder="Date de décès"
                    value="<?php echo ($search_res['death'] ? $search_res['death'] : ""); ?>">
                    État de santé :
                    <input type="text" class="form-control" name="health" placeholder="État de santé"
                    value="<?php echo ($search_res['health'] ? $search_res['health'] : ""); ?>">
                    Annotation :
                    <textarea class="form-control" name="annotation"><?php echo ($search_res['annotation'] ? $search_res['annotation'] : ""); ?></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="modifyInfos(<?php echo $idfollowed ?>)"
                        data-dismiss="modal">Valider</button>
            </div>
        </div>
    </div>
</div>

<div id="geolocModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Add a location for <?php echo ucfirst($search_res['binomial_name']); ?> </h2>
            </div>
            <div class="modal-body">
                <h5>Location</h5>
                <form>
                    <div class="input-group">
                    <label for="mod_latitude">Latitude</label>
                    <input type="number" step="0.0000000001" name="mod_latitude"
                    placeholder="41.02938" required id="mod_latitude">
                    <label for="mod_longitude">Longitude</label>
                    <input type="number" step="0.0000000001" name="mod_longitude"
                    placeholder="0.651098" required id="mod_longitude">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                onclick="write_geoloc_fromodal(<?php echo $idfollowed.','.$idstaff ?>)"
                data-dismiss="modal">Update
            </button>
            </div>
        </div>
    </div>
</div>

<div id="addpic" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Add a picture for <?php echo ucfirst($search_res['binomial_name']); ?> </h2>
            </div>
            <div class="modal-body">
                <h5>Uploead file</h5>
                <form action="upload_pic.php" method="post" enctype="multipart/form-data"
                      id="upload_pic">
                      <div class="form-group">
                    <input type="hidden" name="id" readonly value="<?php echo $idfollowed ?>">
                    <input type="hidden" name="table" readonly value="Followed">
                    <label class="custom-file">
                      <input type="file" name="userpic" class="custom-file-input" placeholder="Chosoe a file">
                      <span class="custom-file-control"></span>
                    </label>
                    <button type="submit" class="btn btn-default">Upload pic</button>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                onclick="write_geoloc_fromodal(<?php echo $idfollowed.','.$idstaff ?>)"
                data-dismiss="modal">Update
            </button>
            </div>
        </div>
    </div>
</div>
