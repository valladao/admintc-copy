<?php defined('BASEPATH') OR exit('No direct script access allowed');

class FixPictures extends CI_Controller {

    /**
     * Controller FixPictures
     *
     * This controller is used to fix product image URLs in the database
     * that may have been incorrectly stored.
     */

    public function __construct()
    {
        parent::__construct();

        $this->load->model('External_db');
        $this->load->model('Shopify_rest');
    }

    /**
     * Check and fix a single product's image URL
     *
     * @param int $idShopify The Shopify ID of the product
     * @return bool True if the image was updated, false otherwise
     */
    public function check_image($idShopify)
    {
        // Get the current image URL from the database
        $this->db->select('picture, idProduct, title');
        $this->db->where('idShopify', $idShopify);
        $query = $this->db->get('products');

        if ($query->num_rows() == 0) {
            echo "Product with Shopify ID {$idShopify} not found in database.<br>";
            return false;
        }

        $product = $query->row();
        $db_picture = $product->picture;

        // Get the image URL from Shopify
        $json_result = $this->Shopify_rest->get_product($idShopify);
        $array_result = json_decode($json_result, true);

        // Check if we got a valid response with an image
        if (!isset($array_result['product']) || !isset($array_result['product']['image']) || !isset($array_result['product']['image']['src'])) {
            echo "Could not get image URL from Shopify for product ID {$idShopify}.<br>";
            return false;
        }

        $shopify_picture = $array_result['product']['image']['src'];

        // Check if the URLs are different
        if ($db_picture != $shopify_picture) {
            // Update the database with the correct URL
            $data['picture'] = $shopify_picture;
            $this->db->where('idShopify', $idShopify);
            $this->db->update('products', $data);

            // Log the update to the database
            $log_message = "Updated image URL for product ID {$product->idProduct} (Shopify ID: {$idShopify}, Title: {$product->title}). Old URL: {$db_picture}, New URL: {$shopify_picture}";
            $this->External_db->externalLog('1', $log_message);

            echo "Updated image URL for product ID {$idShopify}.<br>";
            echo "Old URL: {$db_picture}<br>";
            echo "New URL: {$shopify_picture}<br><br>";

            return true;
        }

        echo "Image URL for product ID {$idShopify} is already correct.<br>";
        return false;
    }

    /**
     * Check and fix all product image URLs
     */
    public function fix_all_images()
    {
        // Set unlimited execution time for long-running process
        ini_set('max_execution_time', '600');
        set_time_limit(600);

        $counter = 0;
        $updated = 0;

        // Get all products with Shopify IDs
        $this->db->select('idShopify');
        $this->db->where('idShopify IS NOT NULL');
        $query = $this->db->get('products');

        $total = $query->num_rows();

        // Log the start of the process
        $this->External_db->externalLog('0', "Starting fix_all_images process. Found {$total} products to check.");

        echo "<h1>Fixing Product Image URLs</h1>";
        echo "<p>Found {$total} products with Shopify IDs to check.</p>";

        // Process each product
        foreach ($query->result() as $row) {
            $counter++;

            // Show progress
            echo "<p>Processing product {$counter} of {$total} (Shopify ID: {$row->idShopify})...</p>";

            // Check and fix the image URL
            if ($this->check_image($row->idShopify)) {
                $updated++;
            }

            // Add a small delay to avoid hitting Shopify API rate limits
            usleep(500000); // 0.5 seconds

            // Flush output to show progress in real-time
            flush();
        }

        // Log the completion of the process
        $this->External_db->externalLog('0', "Completed fix_all_images process. Checked {$counter} products, updated {$updated} products.");

        echo "<h2>Process completed</h2>";
        echo "<p>Total products checked: {$counter}</p>";
        echo "<p>Products updated: {$updated}</p>";
    }

    /**
     * Check and fix product image URLs in batches
     *
     * @param int $offset The offset to start from
     * @param int $limit The number of products to process
     */
    public function fix_batch($offset = 0, $limit = 50)
    {
        // Set unlimited execution time for long-running process
        ini_set('max_execution_time', '300');
        set_time_limit(300);

        $counter = 0;
        $updated = 0;

        // Get products with Shopify IDs in batches
        $this->db->select('idShopify');
        $this->db->where('idShopify IS NOT NULL');
        $this->db->limit($limit, $offset);
        $query = $this->db->get('products');

        $batch_size = $query->num_rows();

        // Get total count for progress indication
        $total_query = $this->db->query("SELECT COUNT(*) as total FROM products WHERE idShopify IS NOT NULL");
        $total = $total_query->row()->total;

        // Log the start of the batch process
        $this->External_db->externalLog('0', "Starting fix_batch process. Batch: {$offset} to " . ($offset + $batch_size) . " of {$total} products.");

        echo "<h1>Fixing Product Image URLs (Batch)</h1>";
        echo "<p>Processing batch: {$offset} to " . ($offset + $batch_size) . " of {$total} products</p>";

        // Process each product in the batch
        foreach ($query->result() as $row) {
            $counter++;

            // Show progress
            echo "<p>Processing product {$counter} of {$batch_size} (Shopify ID: {$row->idShopify})...</p>";

            // Check and fix the image URL
            if ($this->check_image($row->idShopify)) {
                $updated++;
            }

            // Add a small delay to avoid hitting Shopify API rate limits
            usleep(500000); // 0.5 seconds

            // Flush output to show progress in real-time
            flush();
        }

        // Log the completion of the batch process
        $this->External_db->externalLog('0', "Completed fix_batch process. Batch: {$offset} to " . ($offset + $batch_size) . ". Checked {$counter} products, updated {$updated} products.");

        echo "<h2>Batch process completed</h2>";
        echo "<p>Products checked in this batch: {$counter}</p>";
        echo "<p>Products updated in this batch: {$updated}</p>";

        // Show link to next batch if there are more products
        if ($offset + $limit < $total) {
            $next_offset = $offset + $limit;
            echo "<p><a href='" . site_url("fixPictures/fix_batch/{$next_offset}/{$limit}") . "'>Process next batch</a></p>";
        } else {
            echo "<p>All products have been processed.</p>";
        }
    }
}
