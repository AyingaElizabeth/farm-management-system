<?php
require('top.inc.php');


$invoice_directory = "../invoices/"; // Adjust this path to your invoice directory

// Get all files in the directory
$files = scandir($invoice_directory);

// Remove . and .. from the list
$files = array_diff($files, array('.', '..'));
?>

<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="box-title">Invoice Files</h4>
                    </div>
                    <div class="card-body--">
                        <div class="table-stats order-table ov-h">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($files as $file) : 
                                        $file_path = $invoice_directory . $file;
                                        $file_date = date("Y-m-d H:i:s", filemtime($file_path));
                                    ?>
                                        <tr>
                                            <td>
                                                <i class="fa fa-file-pdf-o mr-2"></i>
                                                <?php echo htmlspecialchars($file); ?>
                                            </td>
                                            <td><?php echo $file_date; ?></td>
                                            <td>
                                                
                                                <button onclick="printInvoice('<?php echo $invoice_directory . $file; ?>')" class="btn btn-info btn-sm">
                                                    <i class="fa fa-load mr-1"></i>Download Invoice
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden iframe for printing -->
<iframe id="printFrame" style="display:none;"></iframe>

<script>
function viewInvoice(invoiceUrl) {
    // Extract just the file name from the URL and pass it to the PHP script
    window.open('view_invoice.php?file=' + encodeURIComponent(invoiceUrl.split('/').pop()), '_blank');
}



function printInvoice(invoiceUrl) {
    var frame = document.getElementById('printFrame');
    frame.src = invoiceUrl;
    
    frame.onload = function() {
        try {
            frame.contentWindow.print();
        } catch (e) {
            var printWindow = window.open(invoiceUrl, '_blank');
            printWindow.onload = function() {
                printWindow.print();
            }
        }
    }
}
</script>

<?php
require('footer.inc.php');
?>

