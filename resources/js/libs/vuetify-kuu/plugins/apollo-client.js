import ApolloClient from 'apollo-client';
import { InMemoryCache } from 'apollo-cache-inmemory'
import { createUploadLink } from 'apollo-upload-client'

const cache = new InMemoryCache();
const apolloClient = csrfToken => {
    return new ApolloClient({
        link: createUploadLink({
            uri: '/gql',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
            },
        }),
        cache,
    });
};

export default apolloClient;
