<?php

namespace Potherca\GiFiTy;

function fetch_results(array $arguments)
{
    $results = [];

    $query = array_reduce($arguments['arguments'], function ($carry, $item) {
        $argument = $carry;

        if ($item['name'] === 'search-term') {
            $argument = $item['value'];
        }
        return $argument;
    }, '');

    $data = [];

    if ($query !== '') {
        $params = http_build_query([
          'per_page' => '100',
          'q' => $query.' language:Markdown in:file',
        ]);
        $url = "https://api.github.com/search/code?".$params;

        $data = fetch_url($url);
    }

    /*/ @FIXME: Because of possible false-positives (and many-starred-repo's being
      hidden at the end of the queue) I don't think we can get around fetchin all
      of the pages from the search results.
      This means we need the headers as well to fetch the "next" list.
    /*/

    if (is_array($data) && array_key_exists('items', $data)) {
        array_walk($data['items'], function ($result) use (&$results, $query) {
            $results[] = [
                'file_fragment' => $result['text_matches'][0]['fragment'],
                'file_path' => $result['path'],
                'file_url' => $result['html_url'],
                'repository_description' => $result['repository']['description'],
                'repository_name' => $result['repository']['full_name'],
                'repository_url' => $result['repository']['html_url'],
                'stargazers_url' => $result['repository']['stargazers_url'],
            ];
        });
    }

    /* Add stars per repo */
    array_walk($results, function (&$result) {
        $stargazers = fetch_url($result['stargazers_url']);
        $result['stars'] = count($stargazers);
    });

    /* Sort repo's by star count */
    usort($results, function ($a, $b) {
        return $a['stars'] < $b['stars'];
    });

    /* Create URL to directly edit a file */
    array_walk($results, function (&$result) {
        $parts = explode('/', $result['file_url']);
        $parts['5'] = 'edit';   // blob
        // @FIXME: The main branch MIGHT not be `master`, replace hard-coded value with brach from response object
        $parts['6'] = 'master'; // SHA1 hash
        $result['edit_url'] = implode('/', $parts);
    });

    /* Highlight search term */
    array_walk($results, function (&$result) use ($query) {
        $result['file_fragment'] = str_ireplace(
            $query,
            '<span style="background: yellow;">'.$query.'</span>',
            htmlentities($result['file_fragment'])
        );
    });


    // @FIXME: Multiple results for the same repo need to be added together!
    // @CHECKME: Do we want to do anything for _exactly_ the same matches within one repo?
    // @CHECKME: Repo's can still be clones even if they are not marked as forks! What then?

    return $results;
}

function fetch_url($url)
{
    $githubToken = getenv('GITHUB_TOKEN');
    $githubUser = getenv('GITHUB_USER');

    $ch = curl_init();

    $headers = [
        "Accept: application/vnd.github.v3.text-match+json",
        'User-Agent: http://developer.github.com/v3/#user-agent-required)',
    ];

    $options = [
        CURLOPT_HEADER => false,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_USERPWD => $githubUser.':'.$githubToken,
    ];

    curl_setopt_array($ch, $options);

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        $result = '{"error":"' . curl_error($ch).'"}';
    }

    curl_close ($ch);

    return json_decode($result, true);
}

/*EOF*/
