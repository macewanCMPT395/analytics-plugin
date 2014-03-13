<?php defined('SYSPATH') or die('No direct script access.');

class Analytics_Model extends Model {
	public function __construct()
	{
		parent::__construct(); 
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

	public function get_categories( $category_id = -1 )
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

	public function get_incidents_by_category( $category_id )
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

	/**
	 * Get results by filter
	 *
	 * @param filter the data selection
	 */
	public function get_by_filter( $filter )
	{
		$prefix = $this->db->table_prefix();

		// Build query
		$sql = "";

                // Build query selection
                $is_start = true;    // keep track of the beginning of the query
                $sql .= "SELECT ";

                // Select incident data iff group by category is turned off
                // it does not make sense to display incident data other than
                // total incidents if grouping by category
                if( ! array_key_exists( 'groupByCategory', $filter ) )
                {
                    $sql .= $this->print_comma( ! $is_start ).$prefix."incident.id as incident_id";
                    $sql .= $this->print_comma( true ).$prefix."incident.incident_title as incident_title";
                    $sql .= $this->print_comma( true ).$prefix."incident.incident_description as incident_description";
                    $sql .= $this->print_comma( true ).$prefix."incident.incident_verified as incident_verified";
                    $sql .= $this->print_comma( true ).$prefix."incident.incident_date as incident_date";
                    $sql .= $this->print_comma( true ).$prefix."incident.incident_active as incident_active";

                    $is_start = false;
                }

                if( array_key_exists( 'incidentCount', $filter ) )
                {
                    $sql .= $this->print_comma( ! $is_start )."COUNT ( ".$prefix."incident.id ) as incident_count";

                    $is_start = false;
                }

                if( array_key_exists( 'locationId', $filter ) )
                {
                    $sql .= $this->print_comma( ! $is_start ).$prefix."incident.location_id as location_id";

                    $is_start = false;
                }

                if( array_key_exists( 'locationName', $filter ) )
                {
                    $sql .= $this->print_comma( ! $is_start ).$prefix."location.location_name as location_name";

                    $is_start = false;
                }

                if( array_key_exists( 'locationLatitude', $filter ) )
                {
                    $sql .= $this->print_comma( ! $is_start ).$prefix."location.latitude as location_latitude";

                    $is_start = false;
                }

                if( array_key_exists( 'locationLongitude', $filter ) )
                {
                    $sql .= $this->print_comma( ! $is_start ).$prefix."location.longitude as location_longitude";

                    $is_start = false;
                }
                
                if( array_key_exists( 'countryId', $filter ) )
                {
                    $sql .= $this->print_comma( ! $is_start ).$prefix."country.id as country_id";

                    $is_start = false;
                }

                if( array_key_exists( 'countryName', $filter ) )
                {
                    $sql .= $this->print_comma( ! $is_start ).$prefix."country.country as country_name";

                    $is_start = false;
                }

                if( array_key_exists( 'categoryId', $filter ) )
                {
                    $sql .= $this->print_comma( ! $is_start ).$prefix."category.id as category_id";
                    $sql .= $this->print_comma( true ).$prefix."category.category_color as category_color";

                    $is_start = false;
                }

                if( array_key_exists( 'categoryParentId', $filter ) )
                {
                    $sql .= $this->print_comma( ! $is_start ).$prefix."category.parent_id as parent_id";
                    
                    $is_start = false;
                }

                if( array_key_exists( 'categoryTitle', $filter ) )
                {
                    $sql .= $this->print_comma( ! $is_start ).$prefix."category.category_title as category_title";

                    $is_start = false;
                }

                if( array_key_exists( 'categoryTrusted', $filter ) )
                {
                    $sql .= $this->print_comma( ! $is_start ).$prefix."category.category_trusted as category_trusted";

                    $is_start = false;
                }

                $sql .= " FROM ".$prefix."incident ";

                // Join tables as needed

                // Join category table with incidents
                if( array_key_exists( 'categoryId', $filter ) OR
                    array_key_exists( 'categoryParentId', $filter ) OR
                    array_key_exists( 'categoryTitle', $filter ) OR
                    array_key_exists( 'categoryTrusted', $filter )
                )
                {
                    $sql .= "LEFT JOIN ".$prefix."incident_category 
                             ON ".$prefix."incident.id = ".$prefix."incident_category.incident_id ";
                    $sql .= "LEFT JOIN ".$prefix."category 
                             ON ".$prefix."category.id = ".$prefix."incident_category.category_id ";
                }

                // Join location table with incidents
                $is_location_joined = false;

                if( array_key_exists( 'locationName', $filter ) OR
                    array_key_exists( 'locationLatitude', $filter ) OR
                    array_key_exists( 'locationLongitude', $filter )
                )
                {
                    $sql .= "LEFT JOIN ".$prefix."location ON ".$prefix."incident.location_id = ".$prefix."location.id ";

                    $is_location_joined = true;
                }

                // Join country with incidents
                if( array_key_exists( 'countryId', $filter ) OR
                    array_key_exists( 'countryName', $filter )
                )
                {
                    if( ! $is_location_joined )
                    {
                        $sql .= "LEFT JOIN ".$prefix."location ON ".$prefix."incident.location_id = ".$prefix."location.id ";
                    }

                    $sql .= "LEFT JOIN ".$prefix."country ON ".$prefix."country.id = ".$prefix."location.country_id ";
                }
                
                // Select specified data
                $is_start = true;

                // Disable incident id selection if grouping by category
                if( ! array_key_exists( 'groupByCategory', $filter ) )
                {
                    // Select incident id's
                    if( array_key_exists( 'incidentId', $filter ) )
                    {
                        if( ! empty( $filter[ 'incidentId' ] ) )
                        {
                            $id_array = explode( ' ', $filter[ 'incidentId' ] );
                            $id_list = implode( ',', $id_array );

                            if( $is_start )
                            {
                                $sql .= "WHERE ".$prefix."incident.id IN ( ".$id_list." ) ";
                                $is_start = false;
                            }
                            else
                            {
                                $sql .= "AND ".$prefix."incident.id IN ( ".$id_list." ) ";
                            }
                        }
                    }
                }

                // Select verified incidents
                if( array_key_exists( 'incidentVerified', $filter ) )
                {
                    if( ! empty( $filter[ 'incidentVerified' ] ) )
                    {
                        if( $is_start )
                        {
                            $sql .= "WHERE ".$prefix."incident.incident_verified = '".$filter['incidentVerified']."'";
                            $is_start = false;
                        }
                        else
                        {
                            $sql .= "AND ".$prefix."incident.incident_verified = '".$filter['incidentVerified']."'";
                        }
                    }
                }
                
                // Select country id's
                if( array_key_exists( 'countryId', $filter ) )
                {
                    if( ! empty( $filter[ 'countryId' ] ) )
                    {
                        $id_array = explode( ' ', $filter[ 'countryId' ] );
                        $id_list = implode( ',', $id_array );

                        if( $is_start )
                        {
                            $sql .= "WHERE ".$prefix."location.country_id IN ( ".$id_list." ) ";
                            $is_start = false;
                        }
                        else
                        {
                            $sql .= "AND ".$prefix."location.country_id IN ( ".$id_list." ) ";
                        }
                    }
                }

                // Select category id's
		if( array_key_exists( 'categoryId', $filter ) )
		{
                    if( ! empty( $filter[ 'categoryId' ] ) )
                    {
                        $id_array = explode( ' ', $filter[ 'categoryId' ] );
                        $id_list = implode( ',', $id_array );

                        if( $is_start )
                        {
                            $sql .= "WHERE ".$prefix."category.id IN ( ".$id_list." ) ";
                            $is_start = false;
                        }
                        else
                        {
                            $sql .= 'AND ' . $prefix . 'category.id IN( ' . $id_list . ' ) ';
                        }
                    }
		}
                
                // Select category title
                if( array_key_exists( 'categoryTitle', $filter ) )
                {
                    if( ! empty( $filter[ 'categoryTitle' ] ) )
                    {
                        if( $is_start )
                        {
                            $sql .= "WHERE ".$prefix."category.category_title IN ( ".$filter[ 'categoryTitle' ]." ) ";
                            $is_start = false;
                        }
                        else
                        {
                            $sql .= "AND ".$prefix."category.category_title IN ( ".$filter[ 'categoryTitle' ]." ) ";
                        }
                    }
                }

                // Select trusted categories
		if( array_key_exists( 'categoryTrusted', $filter ) )
                {
		    if( $is_start )
		    {
			$sql .= "WHERE ".$prefix."category.category_trusted IN ( '".$filter[ 'categoryTrusted' ]."' ) ";
			$is_start = false;
		    }
		    else
		    {
			$sql .= "AND ".$prefix."category.category_trusted IN ( '".$filter[ 'categoryTrusted' ]."' )";
		    }
		}

                // Select location id's
		if( array_key_exists( 'locationId', $filter ) )
		{
		    if( ! empty( $filter[ 'locationId' ] ) )
		    {
			$id_array = explode( ' ', $filter[ 'locationId' ] );
			$id_list = implode( ',', $id_array );
			if( $is_start )
			{
			    $sql .= "WHERE ".$prefix."incident.location_id IN ( ".$id_list." ) ";
			    $is_start = false;
			}
			else
			{
			    $sql .= "AND ".$prefix."incident.location_id IN ( ".$id_list." ) ";
			}
		    }
		}

                // Select location name
		if( array_key_exists( 'locationName', $filter ) )
		{
		    if( ! empty( $filter[ 'locationName' ] ) )
		    {
		        if( $is_start )
			{
			    $sql .= "WHERE ".$prefix."incident.location_name IN ( '".$filter[ 'locationName' ]."' ) ";
			    $is_start = false;
			}
		        else
			{
			    $sql .= "AND ".$prefix."incident.location_name IN ( '".$filter[ 'locationName' ]."' ) ";
			}
		    }
		}

                // Select latitude
		if( array_key_exists( 'locationLatitude', $filter ) )
		{
                    if( ! empty( $filter[ 'locationLatitude' ] ) )
                    {
                        $lat_array = explode( ' ', $filter[ 'locationLatitude' ] );
                        $lat_list = implode( ',', $lat_array );
                        if( $is_start )
                        {
                            $sql .= "WHERE ".$prefix."location.latitude IN ( ".$lat_list." ) ";
                            $is_start = false;
                        }
                        else
                        {
                            $sql .= "AND ".$prefix."location.latitude IN ( ".$lat_list." ) ";
                        }
                    }
		}
		
                // Select longitude
		if( array_key_exists( 'locationLongitude', $filter ) )
		{
                    if( ! empty( $filter[ 'locationLongitude' ] ) )
                    {
                        $lon_array = explode( ' ', $filter[ 'locationLongitude' ] );
                        $lon_list = implode( ',', $lon_array );
                        if( $is_start )
                        {
                            $sql .= "WHERE ".$prefix."location.longitude IN ( ".$lon_list." ) ";
                            $is_start = false;
                        }
                        else
                        {
                            $sql .= "AND ".$prefix."location.longitude IN ( ".$lon_list." ) ";
                        }
                    }
		}

                // Select specific date
		if( array_key_exists( 'incidentDate', $filter ) )
		{
                    if( ! empty( $filter[ 'incidentDate' ] ) )
                    {
                        if( $is_start )
                        {
                            $sql .= "WHERE ".$prefix."incident.incident_date IN ( '".$filter[ 'incidentDate' ]."' ) ";
                            $is_start = false;
                        }
                        else
                        {
                            $sql .= "AND ".$prefix."incident.incident_date IN ( '".$filter[ 'incidentDate' ]."' ) ";
                        }
                    }
		}

                // Select starting date
		if( array_key_exists( 'incidentDateFrom', $filter ) )
		{
                    if( ! empty( $filter[ 'incidentDateFrom' ] ) )
                    {
                        if( $is_start )
                        {
                            $sql .= "WHERE ".$prefix."incident.incident_date >= '".$filter[ 'incidentDateFrom' ]."' ";
                            $is_start = false;
                        }
                        else
                        {
                            $sql .= "AND ".$prefix."incident.incident_date >= '".$filter[ 'incidentDateFrom' ]."' ";
                        }
                    }
		}

                // Select stopping date
		if( array_key_exists( 'incidentDateTo', $filter ) )
		{
                    if( ! empty( $filter[ 'incidentDateTo' ] ) )
                    {
                        if( $is_start )
                        {
                            $sql .= "WHERE ".$prefix."incident.incident_date <= '".$filter[ 'incidentDateTo' ]."' ";
                            $is_start = false;
                        }

                        else
                        {
                            $sql .= "AND ".$prefix."incident.incident_date <= '".$filter[ 'incidentDateTo' ]."' ";
                        }
                    }
		}

                // Group by country
		if( array_key_exists( 'groupByCountry', $filter ) )
		{
                    if( ! empty( $filter[ 'groupByCountry' ] ) )
                    {
                        $sql .= "GROUP BY ".$prefix."country.id ";
                    }
		}

                // Group by category
		if( array_key_exists( 'groupByCategory', $filter ) )
		{
                    if( ! empty( $filter[ 'groupByCategory' ] ) )
                    {
                        $sql .= "GROUP BY ".$prefix."category.id ";
                    }
		}

		return $this->db->query( $sql );
	}

        protected function print_comma( $print = false )
        {
            return $print ? "," : "";
        }
}
