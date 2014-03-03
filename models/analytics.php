<?php defined('SYSPATH') or die('No direct script access.');

class Analytics_Model extends Model {
    public function __construct()
    {
        parent::__construct(); 
    }

    public function get_incidents_grouped_by_id()
    {
        $sql = "SELECT COUNT( category.id ) AS count, category.category_title, category.category_color 
                FROM category 
                JOIN incident_category ON category.id=incident_category.category_id 
                JOIN incident ON incident_category.incident_id=incident.id 
                GROUP BY category.id";
        return $this->db->query($sql);
    }

    public function get_categories( $category_id = -1 )
    {
        if( $category_id == -1 )
        {
            $sql = "SELECT category.id, category.category_title, category.category_color 
                    FROM category";
        }
        else
        {
            $sql = "SELECT category.id, category.category_title, category.category_color 
                    FROM category 
                    WHERE category.id=".$category_id;
        }
        return $this->db->query($sql);
    }

    public function get_incidents_by_category( $category_id )
    {
            $sql = "SELECT COUNT( category.id ) AS count, category.id, category.category_title, category.category_color, incident.incident_date 
                    FROM category 
                    JOIN incident_category ON category.id=incident_category.category_id 
                    JOIN incident ON incident_category.incident_id=incident.id 
                    WHERE category.id=".$category_id." 
                    GROUP BY incident.incident_date";
            return $this->db->query($sql);
    }
}
