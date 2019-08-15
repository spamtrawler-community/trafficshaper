<?php

class Firewall_ProjectHoneypot_Helper_ProjectHoneypot Extends \joshtronic\ProjectHoneyPot {
    /**
     * Query
     *
     * Performs a DNS lookup to obtain information about the IP address.
     *
     * @access public
     * @param  string $ip_address IPv4 address to check
     * @return array results from query
     */
    public function query($ip_address)
    {
        // Validates the IP format
        if (filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE))
        {
            // Flips the script, err, IP address
            $octets = explode('.', $ip_address);
            krsort($octets);
            $reversed_ip = implode('.', $octets);

            // Performs the query
            $results = $this->dns_get_record($reversed_ip);

            // Processes the results
            if (isset($results[0]['ip']))
            {
                $results = explode('.', $results[0]['ip']);

                if ($results[0] == 127)
                {
                    $results = array(
                        'last_activity' => $results[1],
                        'threat_score'  => $results[2],
                        'visitortype'    => $results[3],
                    );

                    // Creates an array of categories
                    switch ($results['visitortype'])
                    {
                        case 0:
                            $categories = array('Search Engine');
                            break;

                        case 1:
                            $categories = array('Suspicious');
                            break;

                        case 2:
                            $categories = array('Harvester');
                            break;

                        case 3:
                            $categories = array('Suspicious', 'Harvester');
                            break;

                        case 4:
                            $categories = array('Comment Spammer');
                            break;

                        case 5:
                            $categories = array('Suspicious', 'Comment Spammer');
                            break;

                        case 6:
                            $categories = array('Harvester', 'Comment Spammer');
                            break;

                        case 7:
                            $categories = array('Suspicious', 'Harvester', 'Comment Spammer');
                            break;

                        default:
                            $categories = array('Reserved for Future Use');
                            break;
                    }

                    $results['categories'] = $categories;

                    return $results;
                }
            }
        }
        else
        {
            return array('error' => 'Invalid IP address.');
        }

        return false;
    }
}
