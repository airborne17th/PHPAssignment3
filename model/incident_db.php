<?php

class IncidentDB {

    public static function add_incident($customer_id, $product_code, $title, $description) {
        $db = Database::getDB();
        $date_opened = date('Y-m-d');  // get current date in yyyy-mm-dd format
        $query = 'INSERT INTO incidents
            (customerID, productCode, dateOpened, title, description)
        VALUES (
               :customer_id, :product_code, :date_opened,
               :title, :description)';
        $statement = $db->prepare($query);
        $statement->bindValue(':customer_id', $customer_id);
        $statement->bindValue(':product_code', $product_code);
        $statement->bindValue(':date_opened', $date_opened);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':description', $description);
        $statement->execute();
        $statement->closeCursor();
    }

    public static function getIncidents() {
        $db = Database::getDB();

        $query = 'SELECT * FROM incidents JOIN customers ON incidents.customerID=customers.customerID and incidents.techID is NULL';
        $statement = $db->prepare($query);
        $statement->execute();
        $rows = $statement->fetchAll();
        $statement->closeCursor();
        
        $incidents = array();
        foreach($rows as $row) {
            $i = new Incident(
                    $row['customerID'], $row['productCode'],
                    $row['dateOpened'], $row['title'], $row['description']);
            $i->setID($row['incidentID']);
            $incidents[] = $i;
        }
        return $incidents;
    }
}

?>