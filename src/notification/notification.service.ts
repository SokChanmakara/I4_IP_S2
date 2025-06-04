import { Inject,Injectable } from "@nestjs/common";

@Injectable()
export class NotificationService {
    constructor(@Inject('NOTIFICATION_OPTIONS') private options: any){}

    notify (message:string){
        switch(this.options.type){
            case 'email':
                console.log(`[Email] ${message}`);
                break;
            case 'sms':
                console.log(`[SMS] ${message}`);
                break;
            case 'log':
                console.log(`[Log] ${message}`);
                break;
        }
    }
}