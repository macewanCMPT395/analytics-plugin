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
        
        // Build select statement
        $comma = true;
        $sql .= " SELECT ";

        if( array_key_exists( 'categoryId', $filter ) )
        {
            $sql .= ( $comma ? ' ' : ',' ) . $prefix . 'category.id as category_id';
            $comma = false;
        }

        if( array_key_exists( 'categoryParentId', $filter ) )
        {
            $sql .= ( $comma ? ' ' : ',' ) . $prefix . 'category.parent_id ';
            $comma = false;
        }

        if( array_key_exists( 'categoryTitle', $filter ) )
        {
            $sql .= ( $comma ? ' ' : ',' ) . $prefix . 'category.category_title ';
            $comma = false;
        }

        if( array_key_exists( 'categoryTrusted', $filter ) )
        {
            $sql .= ( $comma ? ' ' : ',' ) . $prefix . 'category.category_trusted ';
            $comma = false;
        }

        if( array_key_exists( 'incidentTotal', $filter ) )
        {
            $sql .= ( $comma ? ' ' : ',' ) . 'COUNT( ' . $prefix . 'incident.id ) as incident_count';
            $comma = false;
        }

        if( ! array_key_exists( 'groupByCategory', $filter ) )
        {
            $sql .= ',' . $prefix . "incident.id ";
            $sql .= ',' . $prefix . 'incident.incident_title ';
            $sql .= ',' . $prefix . 'incident.incident_description ';
            $sql .= ',' . $prefix . 'incident.incident_verified ';
            $sql .= ',' . $prefix . 'incident.incident_date ';
            $sql .= ',' . $prefix . 'incident_active ';
            if( array_key_exists( 'locationId', $filter ) )
                $sql .= ',' . $prefix . 'incident.location_id ';

            if( array_key_exists( 'locationName', $filter ) )
                $sql .= ',' . $prefix . 'location.location_name ';

            if( array_key_exists( 'locationLatitude', $filter ) )
                $sql .= ',' . $prefix . 'location.latitude ';

            if( array_key_exists( 'locationLongitude', $filter ) )
                $sql .= ',' . $prefix . 'location.longitude ';

            if( array_key_exists( 'countryId', $filter ) )
                $sql .= ',' . $prefix . 'country.id as country_id';

            if( array_key_exists( 'countryName', $filter ) )
                $sql .= ',' . $prefix . 'country.country ';
        }


        $sql .= " FROM " . $prefix . "incident ";
        
        // Join tables as needed
        if( array_key_exists( 'categoryId', $filter ) OR 
            array_key_exists( 'categoryParentId', $filter ) OR
            array_key_exists( 'categoryTitle', $filter) OR
            array_key_exists( 'categoryTrusted', $filter ) )
        {
            $sql .= "LEFT JOIN " . $prefix . "incident_category ON ";
            $sql .= $prefix . 'incident.id = ' . $prefix . 'incident_category.incident_id ';

            $sql .= "LEFT JOIN " . $prefix . 'category ON ';
            $sql .= $prefix . 'category.id = ' . $prefix . 'incident_category.category_id ';
        }

        $location = false;
        if( array_key_exists( 'locationName', $filter ) OR
            array_key_exists( 'locationLatitude', $filter ) OR
            array_key_exists( 'locationLongitude', $filter ) )
        {
            $sql .= 'LEFT JOIN ' . $prefix . 'location ON ';
            $sql .= $prefix . 'incident.location_id = ' . $prefix . 'location.id ';
            $location = true;
        }

        if( array_key_exists( 'countryId', $filter ) OR
            array_key_exists( 'countryName', $filter ) )
        {
            if( ! $location )
            {
                $sql .= 'LEFT JOIN ' . $prefix . 'location ON ';
                $sql .= $prefix . 'incident.location_id = ' . $prefix . 'location.id ';
            }

            $sql .= 'LEFT JOIN ' . $prefix . 'country ON ';
            $sql .= $prefix . 'country.id = ' . $prefix . 'location.country_id ';
        }

        if( ! array_key_exists( 'groupByCategory', $filter ) )
        {
            // Select specific data
            $where = true;
            if( array_key_exists( 'incidentId', $filter ) )
            {
                if( ! empty( $filter[ 'incidentId' ] ) )
                {
                    $arr = explode( ' ', $filter[ 'incidentId' ] );
                    $in = implode( ',', $arr );
                    if( $where )
                    {
                        $sql .= 'WHERE ' . $prefix . 'incident.id IN( ' . $in . ' ) ';
                        $where = false;
                    }
                    else
                    {
                        $sql .= 'AND ' . $prefix . 'incident.id IN( ' . $in . ' ) ';
                    }
                }
            }

            if( array_key_exists( 'countryId', $filter ) )
            {
                if( ! empty( $filter[ 'countryId' ] ) )
                {
                    $arr = explode( ' ', $filter[ 'countryId' ] );
                    $in = implode( ',', $arr );
                    if( $where )
                    {
                        $sql .= 'WHERE ' . $prefix . 'location.country_id IN( '. $in . ' )';
                        $where = false;
                    }
                    else
                    {
                        $sql .= 'AND ' . $prefix . "location.country_id IN( " . $in ." )";
                    }
                }
            }
        }

        if( array_key_exists( 'categoryId', $filter ) )
        {
            if( ! empty( $filter[ 'categoryId' ] ) )
            {
                $arr = explode( ' ', $filter[ 'categoryId' ] );
                $in = implode( ',', $arr );

                if( $where )
                {
                    $sql .= 'WHERE ' . $prefix . 'category.id IN( ' . $in . ' ) ';
                    $where = false;
                }
                else
                {
                    $sql .= 'AND ' . $prefix . 'category.id IN( ' . $in . ' ) ';
                }
            }
        }

        if( array_key_exists( 'categoryTitle', $filter ) )
        {
            if( ! empty( $filter[ 'categoryTitle' ] ) )
            {
                //$arr = explode( ' ', $filter[ 'categoryTitle' ] );
                //$in = implode( ',', $arr );

                if( $where )
                {
                    $sql .= 'WHERE ' . $prefix . 'category.category_title IN( ' . $filter[ 'categoryTitle' ] . ' )';
                    $where = false;
                }
                else
                {
                    $sql .= 'AND ' . $prefix . "category.category_title IN( '" . $filter[ 'categoryTitle' ] . "' )";
                }
            }
        }

        if( array_key_exists( 'categoryTrusted', $filter ) )
        {
                if( $where )
                {
                    $sql .= 'WHERE ' . $prefix . 'category.category_trusted IN( ' . $filter[ 'categoryTrusted' ] . ' )';
                    $where = false;
                }
                else
                {
                    $sql .= 'AND ' . $prefix . "category.category_trusted IN( '" . $filter[ 'categoryTrusted' ] . "' )";
                }
        }

        if( array_key_exists( 'locationId', $filter ) )
        {
            if( ! empty( $filter[ 'locationId' ] ) )
            {
                $arr = explode( ' ', $filter[ 'locationId' ] );
                $in = implode( ',', $arr );
                if( $where )
                {
                    $sql .= 'WHERE ' . $prefix . 'incident.location_id IN( '. $in . ' )';
                    $where = false;
                }
                else
                {
                    $sql .= 'AND ' . $prefix . "incident.location_id IN( " . $in ." )";
                }
            }
        }

        if( array_key_exists( 'locationName', $filter ) )
        {
            if( ! empty( $filter[ 'locationName' ] ) )
            {
                if( $where )
                {
                    $sql .= 'WHERE ' . $prefix . 'incident.location_name IN( \''. $filter[ 'locationName' ] . '\' )';
                    $where = false;
                }
                else
                {
                    $sql .= 'AND ' . $prefix . "incident.location_name IN( '" . $filter[ 'locationName' ] ."' )";
                }
            }
        }

        if( array_key_exists( 'locationLatitude', $filter ) )
        {
            if( ! empty( $filter[ 'locationLatitude' ] ) )
            {
                $arr = explode( ' ', $filter[ 'locationLatitude' ] );
                $in = implode( ',', $arr );
                if( $where )
                {
                    $sql .= 'WHERE ' . $prefix . 'location.latitude IN( '. $in . ' )';
                    $where = false;
                }
                else
                {
                    $sql .= 'AND ' . $prefix . "location.latitude IN( " . $in ." )";
                }
            }
        }
        
        if( array_key_exists( 'locationLongitude', $filter ) )
        {
            if( ! empty( $filter[ 'locationLongitude' ] ) )
            {
                $arr = explode( ' ', $filter[ 'locationLongitude' ] );
                $in = implode( ',', $arr );
                if( $where )
                {
                    $sql .= 'WHERE ' . $prefix . 'location.longitude IN( '. $in . ' )';
                    $where = false;
                }
                else
                {
                    $sql .= 'AND ' . $prefix . "location.longitude IN( " . $in ." )";
                }
            }
        }

        if( array_key_exists( 'incidentDate', $filter ) )
        {
            if( ! empty( $filter[ 'incidentDate' ] ) )
            {
                if( $where )
                {
                    $sql .= 'WHERE ' . $prefix . 'incident.incident_date IN( \''. $filter[ 'incidentDate' ] . '\' )';
                    $where = false;
                }
                else
                {
                    $sql .= 'AND ' . $prefix . "incident.incident_date IN( '" . $filter[ 'incidentDate' ] ."' )";
                }
            }
        }

        if( array_key_exists( 'incidentDateFrom', $filter ) )
        {
            if( ! empty( $filter[ 'incidentDateFrom' ] ) )
            {
                if( $where )
                {
                    $sql .= 'WHERE ' . $prefix . 'incident.incident_date >= \''. $filter[ 'incidentDateFrom' ] . '\'';
                    $where = false;
                }
                else
                {
                    $sql .= 'AND ' . $prefix . "incident.incident_date >= '" . $filter[ 'incidentDateFrom' ] ."' ";
                }
            }
        }

        if( array_key_exists( 'incidentDateTo', $filter ) )
        {
            if( ! empty( $filter[ 'incidentDateTo' ] ) )
            {
                if( $where )
                {
                    $sql .= 'WHERE ' . $prefix . 'incident.incident_date <= \''. $filter[ 'incidentDateTo' ] . '\'  ';
                    $where = false;
                }
                else
                {
                    $sql .= 'AND ' . $prefix . "incident.incident_date <= '" . $filter[ 'incidentDateTo' ] ."'  ";
                }
            }
        }

        if( array_key_exists( 'groupByCategory', $filter ) )
        {
            if( ! empty( $filter[ 'groupByCategory' ] ) )
            {
                $sql .= 'GROUP BY '. $prefix . 'category.id';
            }
        }

        if( array_key_exists( 'groupByCountry', $filter ) )
        {
            if( ! empty( $filter[ 'groupByCountry' ] ) )
            {
                $sql .= 'GROUP BY '. $prefix . 'country.id';
            }
        }

        return $this->db->query( $sql );
    }
}
