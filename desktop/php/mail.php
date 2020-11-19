<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('mail');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
    <div class="col-xs-12 eqLogicThumbnailDisplay">
        <legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
        <div class="eqLogicThumbnailContainer">
            <div class="cursor eqLogicAction logoPrimary" data-action="add">
                <i class="fas fa-plus-circle"></i>
                <br/>
                <span>{{Ajouter}}</span>
            </div>
        </div>
        <legend><i class="fas fa-envelope"></i> {{Mes mails}}</legend>
        <input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
        <div class="eqLogicThumbnailContainer">
            <?php
            foreach ($eqLogics as $eqLogic) {
                $opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
                echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
                echo '<img src="' . $plugin->getPathImgIcon() . '" />';
                echo '<br>';
                echo '<span>' . $eqLogic->getHumanName(true, true) . '</span>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <div class="col-xs-12 eqLogic" style="display: none;">
        <div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}</a>
                <a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a>
                <a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
                <a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
        </div>
        <ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a href="" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Commandes}}</a></li>
        </ul>
        <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
            <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                <br/>
                <div class='row'>
                    <div class="col-sm-7">
                        <form class="form-horizontal">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">{{Nom de l'équipement mail}}</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                                        <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement mail}}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                                    <div class="col-sm-5">
																			<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
																				<option value="">{{Aucun}}</option>
																				<?php
																				$options = '';
																				foreach ((jeeObject::buildTree(null, false)) as $object) {
																					$options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
																				}
																				echo $options;
																				?>
																			</select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">{{Catégorie}}</label>
                                    <div class="col-sm-8">
                                        <?php
                                        foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                                            echo '<label class="checkbox-inline">';
                                            echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                                            echo '</label>';
                                        }
                                        ?>
							</div>
						</div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"></label>
                                    <div class="col-sm-9">
                                        <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                                        <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                                    </div>
                                </div>
                                <br/>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">{{Nom expéditeur}}</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="eqLogicAttr form-control" data-l1key='configuration' data-l2key='fromName' />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">{{Mail expéditeur}}</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="eqLogicAttr form-control" data-l1key='configuration' data-l2key='fromMail' />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">{{Mode d'envoi}}</label>
                                    <div class="col-sm-5">
                                        <select class="eqLogicAttr form-control" data-l1key='configuration' data-l2key='sendMode'>
                                            <option value='smtp'>SMTP</option>
                                            <option value='sendmail'>Sendmail</option>
                                            <option value='qmail'>Qmail</option>
                                            <option value='mail'>Mail() [PHP fonction]</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="col-sm-5">
                        <form class="form-horizontal">
                            <fieldset>
                                <div class='sendMode sendmail' style="display: none;">
                                    <div class="alert alert-danger">Attention cette option nécessite d'avoir correctement configurer le système (OS).</div>
                                </div>
                                <div class='sendMode mail' style="display: none;">
                                    <div class="alert alert-danger">Attention cette option nécessite d'avoir correctement configurer le système (OS).</div>
                                </div>
                                <div class='sendMode qmail' style="display: none;">
                                    <div class="alert alert-danger">Attention cette option nécessite d'avoir correctement configurer le système (OS).</div>
                                </div>
                                <div class='sendMode smtp' style="display: none;">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Serveur SMTP}}</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="eqLogicAttr form-control" data-l1key='configuration' data-l2key='smtp::server' />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Port SMTP}}</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="eqLogicAttr form-control" data-l1key='configuration' data-l2key='smtp::port' />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Securité SMTP}}</label>
                                        <div class="col-sm-8">
                                            <select class="eqLogicAttr form-control" data-l1key='configuration' data-l2key='smtp::security'>
                                                <option value=''>{{Aucune}}</option>
                                                <option value='tls'>TLS</option>
                                                <option value='ssl'>SSL</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Utilisateur SMTP}}</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="eqLogicAttr form-control" data-l1key='configuration' data-l2key='smtp::username' />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{Mot de passe SMTP}}</label>
                                        <div class="col-sm-8">
                                            <input type="password" class="eqLogicAttr form-control" data-l1key='configuration' data-l2key='smtp::password' />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"></label>
                                        <div class="col-sm-6">
                                            <label class="control-label"><input type="checkbox" class="eqLogicAttr" data-l1key='configuration' data-l2key='smtp::dontcheckssl' />{{Ne pas verifier le certificat SSL}}</label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="commandtab">
                <a class="btn btn-default btn-sm pull-right cmdAction" data-action="add" style="margin-top:5px;"><i class="fas fa-plus-circle"></i> {{Ajouter une commande mail}}</a>
                <br/><br/>
                <table id="table_cmd" class="table table-bordered table-condensed">
                    <thead>
                        <tr>
							<th style="width: 550px;">{{Nom}}</th>
                            <th style="width: 550px;">{{Email}}</th>
                            <th style="width: 130px"></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
    include_file('desktop', 'mail', 'js', 'mail');
    include_file('core', 'plugin.template', 'js');
?>
