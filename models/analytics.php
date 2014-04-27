<?php defined('SYSPATH') or die('No direct script access.');

class Analytics_Model extends Model {
	public function __construct()
	{
		parent::__construct(); 
	}

        /**
         * Get categories
         * 
         * @param int $category_id the category id to select
         * @return A list of the categories and their attributes
         */
        public function get_categories( $category_id = null )
        {
            $prefix = $this->db->table_prefix();    // get table prefix

            $sql = '';
            $sql .= "SELECT ".$prefix."category.id AS category_id, "
                             .$prefix."category.category_title AS category_title, "
                             .$prefix."category.category_description AS category_description, "
                             .$prefix."category.category_color AS category_color, "
                             .$prefix."category.category_trusted AS category_trusted, "
                             .$prefix."category.parent_id AS category_parent ";
            $sql .= "FROM ".$prefix."category ";

            if( ! empty( $category_id ) )
            {
                $sql .= "WHERE ".$prefix."category.id=".$category_id;
            }

            return $this->db->query( $sql );
        }

        /**
         * Get countries
         *
         * @param int $country_id the country id to select
         * @return a list of the countries and their attributes
         */
        public function get_countries( $country_id = null )
        {
            $prefix = $this->db->table_prefix();    // get table prefix

            $sql = '';
            $sql .= "SELECT ".$prefix."country.id AS country_id, "
                             .$prefix."country.country AS country_name, "
                             .$prefix."country.capital AS country_capital, "
                             .$prefix."country.cities AS country_cities ";
            $sql .= "FROM ".$prefix."country ";

            if( ! empty( $category_id ) )
            {
                $sql .= "WHERE ".$prefix."country.id=".$country_id;
            }

            return $this->db->query( $sql );
        }
        
        /**
         * Get incidents by category
         * 
         * @param string $keyword a keyword to search for
         * @param int $category_id a category to select
         * @param bool $group_by_country group countries together
         * @param datetime $date_from a date start from
         * @param datetime $date_to a date to stop at
         * @return incidents by category
         */
        public function get_incidents( $keyword = null, $category_id = array(), $country_id = array(), $group_by_country = false, $date_from = null, $date_to = null)
        {
            $prefix = $this->db->table_prefix();

            $sql = '';
            $sql .= "SELECT COUNT( ".$prefix."incident.id ) AS incident_count, "
                                    .$prefix."incident.location_id AS incident_location, "
                                    .$prefix."incident.id AS incident_id, "
                                    .$prefix."incident.incident_title AS incident_title, "
                                    .$prefix."incident.incident_description AS incident_description, "
                                    .$prefix."incident.incident_date AS incident_date, "
                                    .$prefix."incident.incident_active AS incident_active, "
                                    .$prefix."incident.incident_verified AS incident_verified, "
                                    .$prefix."incident.location_id AS incident_location, ";
            $sql .=  $prefix."category.id AS category_id, "
                    .$prefix."category.category_title AS category_title, "
                    .$prefix."category.category_description AS category_description, "
                    .$prefix."category.category_color AS category_color, "
                    .$prefix."category.category_trusted AS category_trusted, ";
            $sql .= $prefix."location.country_id AS country_id, "
                   .$prefix."location.location_name AS location_name ";
            $sql .= "FROM ".$prefix."incident ";
            $sql .= "LEFT JOIN ".$prefix."incident_category ".
                    "ON ".$prefix."incident.id=".$prefix."incident_category.incident_id ".
                    "LEFT JOIN ".$prefix."category ".
                    "ON ".$prefix."incident_category.category_id=".$prefix."category.id ".
                    "LEFT JOIN ".$prefix."location ".
                    "ON ".$prefix."incident.location_id=".$prefix."location.id ";

            $is_start = true;
            if( ! empty( $category_id ) )
            {
                $sql .= ($is_start ? " WHERE " : " AND ").$prefix."category.id=".$category_id." ";
                $is_start = false;
            }

            if( ! empty( $date_from ) )
            {
                $sql .= ($is_start ? " WHERE " : " AND ").$prefix."incident_date >= '".$date_from."' ";
                $is_start = false;
            }

            if( ! empty( $date_to ) )
            {
                $sql .= ($is_start ? " WHERE " : " AND ").$prefix."incident_date <= '".$date_to."' ";
                $is_start = false;
            }

            if( ! empty( $keyword ) )
            {
                $sql .= ($is_start ? " WHERE " : " AND ").$prefix."incident_description LIKE '%".$keyword."%' ";
                $is_start = false;
            }

            if( $group_by_country == true )
            {
                $sql .= "GROUP BY ".$prefix."incident.location_id ";
            }
            else
            {
                $sql .= "GROUP BY ".$prefix."incident.incident_date ";
            }

            return $this->db->query( $sql );
        }

        public function get_incidents_by_id( $keyword = null, $category_id = array(), $country_id = array(), $date_from = null, $date_to = null )
        {
		$prefix = $this->db->table_prefix();
		$sql = "SELECT COUNT( ".$prefix."category.id ) AS incident_count, "
                                       .$prefix."category.category_title AS category_title, "
                                       .$prefix."category.category_color AS category_color, "
                                       .$prefix."incident.location_id AS location_id, "
                                       .$prefix."location.country_id AS country_id, "
                                       .$prefix."location.location_name AS location_name 
				FROM ".$prefix."category 
				JOIN ".$prefix."incident_category ON ".$prefix."category.id=".$prefix."incident_category.category_id 
				JOIN ".$prefix."incident ON ".$prefix."incident_category.incident_id=".$prefix."incident.id 
                                JOIN ".$prefix."location ON ".$prefix."incident.location_id=".$prefix."location.id ";
                $is_start = true;
                if( ! empty( $category_id ) )
                {
                    $category_in = implode( ', ', $category_id );
                    $sql .= ($is_start ? " WHERE " : " AND ").$prefix."category.id IN(".$category_in.") ";
                    $is_start = false;
                }

                if( ! empty( $country_id ) )
                {
                    $country_in = implode( ", ", $country_id );
                    $sql .= ($is_start ? " WHERE " : " AND ").$prefix."incident.location_id IN(".$country_in.") ";
                    $is_start = false;
                }


                if( ! empty( $date_from ) )
                {
                    $sql .= ($is_start ? " WHERE " : " AND ").$prefix."incident_date >= '".$date_from."' ";
                    $is_start = false;
                }

                if( ! empty( $date_to ) )
                {
                    $sql .= ($is_start ? " WHERE " : " AND ").$prefix."incident_date <= '".$date_to."' ";
                    $is_start = false;
                }

                if( ! empty( $keyword ) )
                {
                    $sql .= ($is_start ? " WHERE " : " AND ").$prefix."incident_title LIKE '%".$keyword."%' ";
                    $is_start = false;
                }

	        $sql .= "GROUP BY ".$prefix."category.id ";
		return $this->db->query($sql);
        }

	public function get_incidents_grouped_by_id()
	{
		$prefix = $this->db->table_prefix();
		$sql = "SELECT COUNT( ".$prefix."category.id ) AS count, ".$prefix."category.category_title, ".$prefix."category.category_color 
				FROM ".$prefix."category 
				JOIN ".$prefix."incident_category ON ".$prefix."category.id=".$prefix."incident_category.category_id 
				JOIN ".$prefix."incident ON ".$prefix."incident_category.incident_id=".$prefix."incident.id 
				GROUP BY ".$prefix."category.id";
		return $this->db->query($sql);
	}

	public function _get_categories( $category_id = -1 )
	{
		$prefix = $this->db->table_prefix();
		if( $category_id == -1 )
		{
			$sql = "SELECT ".$prefix."category.id, ".$prefix."category.category_title, ".$prefix."category.category_color 
					FROM ".$prefix."category";
		}
		else
		{
			$sql = "SELECT ".$prefix."category.id, ".$prefix."category.category_title, ".$prefix."category.category_color 
					FROM ".$prefix."category 
					WHERE ".$prefix."category.id=".$prefix . $category_id;
		}

		return $this->db->query($sql);
	}

	public function _get_incidents_by_category( $category_id )
	{
		$prefix = $this->db->table_prefix();
		$sql = "SELECT COUNT( ".$prefix."category.id ) AS count, ".$prefix."category.id, ".$prefix."category.category_title, ".$prefix."category.category_color, ".$prefix."incident.incident_date 
				FROM ".$prefix."category 
				JOIN ".$prefix."incident_category ON ".$prefix."category.id=".$prefix."incident_category.category_id 
				JOIN ".$prefix."incident ON ".$prefix."incident_category.incident_id=".$prefix."incident.id 
				WHERE ".$prefix."category.id=".$category_id." 
				GROUP BY ".$prefix."incident.incident_date";
		return $this->db->query($sql);
	}

    public function get_incidents_table_D3_pc()
    {
	$p = $this->db->table_prefix();

	$sql = "
	    SELECT 
	    ".$p."category.category_title AS Category, 
	    ".$p."country.country AS Country,
	    ".$p."incident.incident_verified AS Verified, 
	    ".$p."category.category_trusted AS 'Category Trusted',
	    ".$p."incident.incident_date AS 'Date (Unix)',
	    ".$p."incident.id AS 'Incident #'

	    FROM ".$p."incident INNER JOIN  ".$p."incident_category ON  ".$p."incident.id = ".$p."incident_category.incident_id
	    INNER JOIN ".$p."category ON ".$p."incident_category.category_id = ".$p."category.id
	    INNER JOIN ".$p."location ON ".$p."incident.location_id = ".$p."location.id
	    INNER JOIN ".$p."country ON ".$p."location.country_id = ".$p."country.id"
	;

	return $this->db->query( $sql );
    }
}
