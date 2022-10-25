<?php

use CMW\Manager\Lang\LangManager;

$title = LangManager::translate("contact.history.title");
$description = LangManager::translate("contact.history.description");

$scripts = '
<script>
    $(function () {
        $("#users_table").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            language: {
                processing:     "' . LangManager::translate("core.datatables.list.processing") . '",
                search:         "' . LangManager::translate("core.datatables.list.search") . '",
                lengthMenu:    "' . LangManager::translate("core.datatables.list.lenghtmenu") . '",
                info:           "' . LangManager::translate("core.datatables.list.info") . '",
                infoEmpty:      "' . LangManager::translate("core.datatables.list.info_empty") . '",
                infoFiltered:   "' . LangManager::translate("core.datatables.list.info_filtered") . '",
                infoPostFix:    "' . LangManager::translate("core.datatables.list.info_postfix") . '",
                loadingRecords: "' . LangManager::translate("core.datatables.list.loadingrecords") . '",
                zeroRecords:    "' . LangManager::translate("core.datatables.list.zerorecords") . '",
                emptyTable:     "' . LangManager::translate("core.datatables.list.emptytable") . '",
                paginate: {
                    first:      "' . LangManager::translate("core.datatables.list.first") . '",
                    previous:   "' . LangManager::translate("core.datatables.list.previous") . '",
                    next:       "' . LangManager::translate("core.datatables.list.next") . '",
                    last:       "' . LangManager::translate("core.datatables.list.last") . '"
                },
                aria: {
                    sortAscending:  "' . LangManager::translate("core.datatables.list.sort.ascending") . '",
                    sortDescending: "' . LangManager::translate("core.datatables.list.sort.descending") . '"
                }
            },
        });
    });
</script>'; ?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= LangManager::translate("contact.history.title") ?></h3>
                    </div>
                    <div class="card-body">
                        <table id="users_table" class="table table-bordered">
                            <thead>
                            <tr>
                                <th><?= LangManager::translate("contact.message.id") ?></th>
                                <th><?= LangManager::translate("contact.message.name") ?></th>
                                <th><?= LangManager::translate("contact.message.object") ?></th>
                                <th><?= LangManager::translate("contact.message.date") ?></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php /* @var \CMW\Entity\Contact\ContactEntity[] $messages */
                            foreach ($messages as $message) : ?>
                                <tr>
                                    <td><?= $message->getId() ?></td>
                                    <td><?= mb_strimwidth($message->getName(), 0, 35, '...') ?></td>
                                    <td><?= mb_strimwidth($message->getObject(), 0, 35, '...') ?></td>
                                    <td><?= $message->getDate() ?></td>
                                    <td class="text-center">
                                        <a href="read/<?= $message->getId() ?>"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th><?= LangManager::translate("contact.message.id") ?></th>
                                <th><?= LangManager::translate("contact.message.name") ?></th>
                                <th><?= LangManager::translate("contact.message.object") ?></th>
                                <th><?= LangManager::translate("contact.message.date") ?></th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


