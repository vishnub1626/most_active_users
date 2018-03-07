<?php

require('./vendor/autoload.php');

function getMostActiveUsers($users)
{
    $countryWithUsers = collect($users)->mapToGroups(function ($item, $key) {
        return [$item['country'] => $item];
    });

    $countriesWithMostActiveUsers = collect();
    foreach ($countryWithUsers as $country) {
        $mostActiveMaleUser = getMostActiveUser($country->where('gender', 'M'));
        $mostActiveFemaleUser = getMostActiveUser($country->where('gender', 'F'));

        $countriesWithMostActiveUsers->push([
            'country' => $country->first()['country'],
            'M' => getUserInfo($mostActiveMaleUser),
            'F' => getUserInfo($mostActiveFemaleUser),
        ]);
    }
    return $countriesWithMostActiveUsers->sortByDesc(function ($country) {
        return getActivityScore($country['M']) + getActivityScore($country['F']);
    });
}

function getMostActiveUser($users)
{
    return $users->where('activity_score', $users->max('activity_score'))->first();
}

function getUserInfo($user) {
    if (!empty($user)) {
        return [
            'id' => $user['id'],
            'activity_score' => $user['activity_score'],
            'age' => date("Y") - $user['dob'],
        ];
    }
    return null;
}

function getActivityScore($user)
{
    return $user['activity_score'] ? $user['activity_score'] : 0;
}




$users = [
    ['id' => 1, 'gender' => 'M', 'dob' => 1990, 'country' => 'IN', 'activity_score' => 34],
    ['id' => 2, 'gender' => 'M', 'dob' => 1980, 'country' => 'US', 'activity_score' => 9],
    ['id' => 3, 'gender' => 'F', 'dob' => 1993, 'country' => 'UK', 'activity_score' => 45],
    ['id' => 4, 'gender' => 'M', 'dob' => 1998, 'country' => 'IN', 'activity_score' => 0],
    ['id' => 5, 'gender' => 'F', 'dob' => 1997, 'country' => 'IN', 'activity_score' => 234],
    ['id' => 6, 'gender' => 'M', 'dob' => 1991, 'country' => 'UK', 'activity_score' => -6],
    ['id' => 7, 'gender' => 'F', 'dob' => 1992, 'country' => 'JP', 'activity_score' => 9],
    ['id' => 8, 'gender' => 'M', 'dob' => 1998, 'country' => 'US', 'activity_score' => 45],
    ['id' => 9, 'gender' => 'F', 'dob' => 2000, 'country' => 'JP', 'activity_score' => 5],
    ['id' => 10, 'gender' => 'M', 'dob' => 2006, 'country' => 'IN', 'activity_score' => 7],
    ['id' => 11, 'gender' => 'F', 'dob' => 1970, 'country' => 'US', 'activity_score' => 32],
    ['id' => 12, 'gender' => 'M', 'dob' => 2011, 'country' => 'IN', 'activity_score' => 21],
];
var_dump(getMostActiveUsers($users));