import { Injectable, NestMiddleware } from "@nestjs/common";
import { NextFunction } from "express";


@Injectable()
export class LoggerMiddleware implements NestMiddleware {
    use(req: any, res: any, next: NextFunction){
        console.log(`[${new Date().toISOString()}] ${req.method} ${req.originalUrl || req.url}`);
        next();
    }
}