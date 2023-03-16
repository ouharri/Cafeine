/**
 * Get a monsterlink from an id.
 *
 * @since 2.3.0
 *
 * @param  {string} id Campaign Id.
 *
 * @returns {string}    Campaign monsterlink url.
 */
export const getMonsterlink = (id) => OMAPI.monsterlink + id + '/';
