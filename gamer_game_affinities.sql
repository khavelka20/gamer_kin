SELECT 
	MAX(gen.name) as genre_name,
    ge.genre_id,
    SUM((gg.time_played * ifnull(gg.rating, 1)) / ge.rank) AS affinity
FROM
    gamerkin.gamer_games gg
        LEFT JOIN
    gamerkin.game_genres ge ON gg.game_id = ge.game_id
        LEFT JOIN
    gamerkin.genres gen ON ge.genre_id = gen.id
WHERE
    gg.gamer_id = 3 AND gg.time_played > 0
    AND ge.genre_id IS NOT NULL
GROUP BY (ge.genre_id);


/*SELECT 
    g.name, ge.name, gg.time_played, ggen.rank
FROM
    gamerkin.gamer_games gg
        LEFT JOIN
    gamerkin.games g ON gg.game_id = g.id
        LEFT JOIN
    gamerkin.game_genres ggen ON gg.game_id = ggen.game_id
        LEFT JOIN
    gamerkin.genres ge ON ge.id = ggen.genre_id
WHERE
    ge.name = 'Difficult'*/