<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">       
        <title>Extras - Il Ritrovo</title>
        <link href="/IlRitrovo/src/Smarty/css/styles.css" rel="stylesheet">
        <link href="/IlRitrovo/src/Smarty/css/extra.css" rel="stylesheet">
    </head>
    <body>

        <!-- Header incluso tramite View-->

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
                                <strong>Name:</strong> <span>{$extra->getName()}</span>
                                <strong>Price:</strong> <span>{$extra->getPrice()} €</span>

                                <div class="extra-actions">
                                    <a href="/IlRitrovo/public/Extra/editExtra/{$extra->getIdExtra()}" class="edit-btn-circle">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                    <form method="POST" action="/IlRitrovo/public/Extra/deleteExtra/{$extra->getIdExtra()}">
                                        <button class="delete-btn"><i class="fa fa-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {/foreach}
                    {else}
                        <p>No extras available at the moment.</p>
                    {/if}
                </div> <!--/.extra-list-->
                <!-- Form per aggiungere un nuovo extra -->
                <div class="extra-form-container" {if $show_extra_form}style="display: block;"{else}style="display: none;">
                    <form action="/IlRitrovo/public/Extra/addExtra" method="POST" id="add-extra-form">
                        <label for="name">Extra Name:</label>
                        <input type="text" id="name" name="name" required><br><br>
                        <label for="price">Price:</label>
                        <input type="number" id="price" name="price" step="0.01" required><br><br>
                        <button type="submit">Add Extra</button>                  
                    </form>
                </div>
                <!-- Pulsante per aggiungere un nuovo extra -->
                <form action="/IlRitrovo/public/Extra/showAddExtra" method="POST">
                    <input type="hidden" name="action" value="show_form">
                    <button type="submit" class="btn btn-primary add-extra-btn">+ Add New Extra</button>
                </form>
            </div> <!--/.panel-body-->
        </div> <!--/.panel-default-->

        <!-- Footer -->
        {include file='footerAdmin.tpl'}
    </body>
</html>