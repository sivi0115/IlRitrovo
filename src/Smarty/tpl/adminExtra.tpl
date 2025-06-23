<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">       
        <title>Extras - Il Ritrovo</title>
        <link href="../css/styles.css" rel="stylesheet">
        <link href="../css/extra.css" rel="stylesheet">
    </head>
    <body>
        <!-- Header -->
        {include file='headerAdmin.tpl'}

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Extras</h3>
            </div> <!--/.panel-heading-->
            <div class="panel-body">
                <!-- Lista degli extra esistenti -->
                <div class="extra-list">
                    {if $extras|@count > 0}
                        {foreach from=$extras item=extra}
                        <div class="extra-item">
                            <div class="extra-info">
                            {if $extra.isEditing}
                                <form method="POST" action="CFrontController.php?controller=CExtra&task=saveEditExtra&id={$extra->getIdExtra()}">
                                <label>Name:</label>
                                <input type="text" name="name" value="{$extra->getName()}" class="editable-input" required>

                                <label>Price:</label>
                                <input type="number" name="price" value="{$extra->getPrice()}" class="editable-input" step="0.01" required> €
                                
                                <button type="submit" class="edit-btn-circle"><i class="fas fa-save"></i> Save</button>
                                </form>
                            {else}
                                <strong>Name:</strong> <span>{$extra->getName()}</span>
                                <strong>Price:</strong> <span>{$extra->getPrice()} €</span>

                                <div class="extra-actions">
                                <form method="GET" action="CFrontController.php?controller=CExtra&task=editExtra&id={$extra->getIdExtra()}">
                                    <button class="edit-btn-circle"><i class="fas fa-pencil-alt"></i> Edit</button>
                                </form>
                                <form method="POST" action="CFrontController.php?controller=CExtra&task=deleteExtra&id={$extra->getIdExtra()}">
                                    <button class="delete-btn"><i class="fa fa-trash"></i> Delete</button>
                                </form>
                                </div>
                            {/if}
                            </div>
                        </div>
                        {/foreach}
                    {else}
                        <p>No extras available at the moment.</p>
                    {/if}
                </div> <!--/.extra-list-->
                <!-- Form per aggiungere un nuovo extra -->
                <div class="extra-form-container" {if $show_extra_form}style="display: block;"{else}style="display: none;">
                    <form action="CFrontController.php?controller=CExtra&task=addExtra" method="POST" id="add-extra-form">
                        <label for="name">Extra Name:</label>
                        <input type="text" id="name" name="name" required><br><br>
                        <label for="price">Price:</label>
                        <input type="number" id="price" name="price" step="0.01" required><br><br>
                        <button type="submit">Add Extra</button>                  
                    </form>
                </div>
                <!-- Pulsante per aggiungere un nuovo extra -->
                <form action="CFrontController.php?controller=CExtra&task=showAddExtra" method="POST">
                    <input type="hidden" name="action" value="show_form">
                    <button type="submit" class="btn btn-primary add-extra-btn">+ Add New Extra</button>
                </form>
            </div> <!--/.panel-body-->
        </div> <!--/.panel-default-->

        <!-- Footer -->
        {include file='footerAdmin.tpl'}
    </body>
</html>