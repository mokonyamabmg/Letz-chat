export default class Gate {


    constructor(user)
    {
        this.user = user;
    }

    isAdmin() {
        return this.user.type === 'Admin';

    }

    isUser() {
        return this.user.type === 'Standard User';
    }

    isAuthor() {
        return this.user.type === 'Author';
    }


    isAdminOrAuthor() {

        if(this.user.type === 'Admin' ||  this.user.type === 'Author')
        {
            return true;
        }
    }

    isAuthorOrUser() {

        if(this.user.type === 'Standard User' ||  this.user.type === 'Author')
        {
            return true;
        }
    }
}
