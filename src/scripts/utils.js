// delay between promises using .delay()
export const delay = ( ms ) => new Promise( ( f ) => setTimeout( f, ms ) );