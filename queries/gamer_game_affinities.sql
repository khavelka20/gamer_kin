SELECT 
    gg.game_id,gg.time_played, ge.rank, gen.name
FROM
    gamerkin.gamer_games gg
        LEFT JOIN
    gamerkin.game_genres ge ON gg.game_id = ge.game_id
		LEFT JOIN
	gamerkin.genres gen ON ge.genre_id = gen.id
WHERE
    gg.gamer_id = 3 AND ge.rank <= 5
ORDER BY gg.game_id, ge.rank asc